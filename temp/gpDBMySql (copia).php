<?php
/**
 * Classe gestione db Mysql 
 */
class gpDBMySql
{
     private $mysqli = null;
     var $error = false; // true se una query da un errore
     var $tablesList = array(); // l'elenco delle tabelle
     var $prefix = ''; // l'elenco delle tabelle
    /*
    * COSTRUCTOR INIZIALIZZA LA CONNESSIONE
    * @params 
    */
    function __construct($ip, $login, $pass, $dbname) 
    {
        $mysqli_temp = new mysqli($ip, $login, $pass, $dbname);
        if ($mysqli_temp->connect_errno) 
        {
            /*
            echo "Sorry, this website is experiencing problems. ";
            echo " Error: Failed to make a MySQL connection, here is why: \n";
            echo " Errno: " . $mysqli_temp->connect_errno . "\n";
            echo " Error: " . $mysqli_temp->connect_error . "\n";
            */
            $this->error=  true;
        } else {
            $this->mysqli = $mysqli_temp;
        }
    }
    /**
     *  Setta il prefix
     */
    function setPrefix($prefix) 
    {
        $this->prefix = $prefix;
    }
    /**
     * Esegue una query
     * @param String $sql 
     */
    function query($sql) 
    {
        $this->error = false;
        if($this->mysqli == null)
        {   
            $this->error = true;
            return false;
        }
        $sql = $this->sqlPrefix($sql);
        $ris = $this->mysqli->query($sql);
        if ($ris === false) 
        {
            $this->error = true;
        }
        return $ris;
    }
     /**
     * Esegue una query Select
     * @param String $sql 
     */
    function getResults($sql) 
    {
        $this->error = false;
        if($this->mysqli == null)
        {   
            $this->error = true;
            return false;
        }
        if (!$result = $this->mysqli->query($sql)) 
        {
            $this->error = true;
            return false;
        }
        $data = array();
        while($row = mysqli_fetch_assoc($result))
        {
            $data[] = $row;
        }
        return $data;
    }
    /**
     * getTables ritorna l'elenco delle tabelle
     * @param Boolean $cache default true
     * @return Array The names of the tables
     */
    function getTables($cache = true) {
        $ris = array();
        if ($cache && $this->tablesList != false) {
            return $this->tablesList;
        }
        $tables = $this->mysqli->query('SHOW TABLES');
        while($row = mysqli_fetch_assoc($tables))
        {
            $ris[] = array_shift($row);
        }
        $this->tablesList = $ris;
        return $ris;
    }
    /**
     * Ritorna un array con due valori: 'fields' contiene l'elenco delle colonne, 'key' il nome della  primary key
     * @param String $tableName primary key
     * @param Boolean $cache default true
     * @return Array fields and key
     */
    function describes($tableName, $cache = true) {
        $ris = array();
         if ($cache && array_key_exists($tableName, $this->fieldsList) != false) {
            return $this->fieldsList[$tableName];
        }
        $fields = $this->mysqli->query('DESCRIBE '.$this->quoteName($tableName)); 
        while($row = mysqli_fetch_assoc($fields))
        {
           $ris[$row['Field']] = $row['Type'];
           if ($row['Key'] == "PRI") {
               $primary = $row['Field'];
           }
        }
        $this->fieldsList[$tableName] = array('fields'=>$ris, 'key'=>$primary);
        return $this->fieldsList[$tableName];
    }
    /**
     * Esegue una query Select e restituisce la prima riga
     * @param String $sql 
     * @param Integer $offset Default 0, Sceglie la riga da scaricare
     * @return Array
     */
    function getRow($sql, $offset = 0) 
    {
        $this->error = false;
        if($this->mysqli == null)
        {    
            $this->error = true;
            return false;
        }
       
        if (!$result = $this->mysqli->query($sql)) 
        {
            $this->error = true;
            return false;
        }
        $k = 0;
        while($row = mysqli_fetch_assoc($result))
        {
            if ($k == $offset) {
                return $row;
            }
            $k++;
        }
        return $row;
    }
     /**
     * Esegue una query Select e restituisce la prima riga
     * @param String $sql 
     * @param Integer $offset Default 0, Sceglie la riga da scaricare
     */
    function getVar($sql, $offset = 0)
    {
        $this->error = false;
        if($this->mysqli == null)
        {   
            $this->error = true;
            return false;
        }
        if (!$result = $this->mysqli->query($sql)) 
        {
            $this->error = true;
            return false;
        }
        $k = 0;
        while($row = mysqli_fetch_assoc($result))
        {
            if ($k == $offset) {
                return array_shift($row);
            }
            $k++;
        }
        return array_shift($row);
    }
     /**
     * Fa l'insert ad una tabella 
     * @param String $table
     * @param Array $data
     * @param Boolean queryText if true return the sql query without execute
     * @return Boolean
     */
    function insert($table, $data) 
    {
        $this->error = false;
        $field = array();
        $values = array();
        $tableFields = $this->describes($table);
        foreach ($data as $key=>$val) 
        {
            if (array_key_exists($key, $tableFields['fields'])) {
                $field[] = $this->quoteName($key);
                $values[] = $this->quote($val);
            }
        }
        if (count($values) > 0) 
        {
            $query = "INSERT INTO ".$this->quoteName($table)." (".implode(", ", $field)." ) VALUES (".implode(", ", $values).");";
            $resQuery = $this->query($query);
            if (!$this->error) {
                return $this->mysqli->insert_id;
            } else {
                return false;
            }
           
        } else {
            $this->error = true;
        }
        return false;
    }
    /** 
     *  Elimina le righe dal db
     * @param String $table
     * @param Array $where
     * @return Boolean
     */
    function delete($table, $where) 
    {
        $this->error = false;
        $values = array();
        $tableFields = $this->describes($table);
        foreach ($where as $key=>$val) 
        {
            if (array_key_exists($key, $tableFields['fields'])) {
                $values[] = $this->quoteName($key)." = ". $this->quote($val);
            } else {
                $this->error = true;
                return false;
            }
        }
        if (count($values) > 0) 
        {
            $query = "DELETE FROM ".$this->quoteName($table)." WHERE ".implode(", ", $values).";";
            return $this->query($query);
        } else {
            $this->error = true;
        }
        return false;
    }
    /**
     * Esegue una serie di query divise da ; 
     * E' asincrona per cui non bisogna aspettare la risposta del server
     * @param String $sql
     */
    function multiQuery($sql) 
    {
        $this->error = false;
        if($this->mysqli == null) 
        {   
            $this->error = true;
            return false;
        }
        return $this->mysqli->multi_query($sql);
    }
    /**
     * Fa l'update ad una tabella con i dati where
     * @param String $table
     * @param Array $data
     * @param Array $where
     * @return Boolean
     */
    function update($table, $data, $where) 
    {
        $this->error = false;
        $field = array();
        $values = array();
        $tableFields = $this->describes($table);
        foreach ($data as $key=>$val) 
        {
            if (array_key_exists($key, $tableFields['fields'])) {
                $field[] = $this->quoteName($key)." = ". $this->quote($val);
            }
        }
        foreach ($where as $key=>$val) 
        {
            if (array_key_exists($key, $tableFields['fields'])) {
                $values[] = $this->quoteName($key)." = ". $this->quote($val);
            } else {
                $this->error = true;
                return false;
            }
        }
        if (count($values) > 0) 
        {
            $query = "UPDATE ".$this->quoteName($table)." SET ".implode(", ", $field)."  WHERE ".implode(", ", $values).";";
           // print "<p>".$query."</p>";
            return $this->query($query);
          
        } else {
            $this->error = true;
        }
        return false;
    }
    /**
     * GET Last query insert id
     * @return Integer
     */
    function insertid() {
        return $this->mysqli->insert_id;
    }
    
    /**
     * QUOTE name or table
     * @param String $val
     * @return String
     */
    function quoteName($val) {
        return '`'.$val.'`';
    }
    /**
     * Quote value
     *  @param String $val
     * @return String
     */
    function quote($val) {
        return "'".$this->mysqli->real_escape_string($val)."'";
    }

    /**
     *  Prepara un array per essere compatibile con insert/update 
     *  Quindi selezionando solo i campi di arrayConvert che dice anche come si chiamano nel db
     *  e trasformando eventualmente i campi attraverso arrayType e arrayFunction 
     *  $db->prepare($_POST, 
            array('id'=>'postId', 'title'=>'postTitle'), 
            array('id'=>'int', 'title'=>'customFn'), 
            array('customFn'=> function($field) { return strip_tags($field); } )
        );
     * @param Array $data i dati da elaborare
     * @param Array $fields  key/value  dove key è la chiave di data e value è il campo field del database
     * @param Array $arrayType dove key è il nome del campo del db e value è la funzione di elaborazione dei dati
     * @param Array $arrayFunction le funzioni per elaborare i dati
     * @return Array
     * 
     */
    function prepare($dataField, $arrayConvert, $arrayType = array(), $arrayFunction = array()) 
    {
        $this->error = false;
        $fields = array();
        foreach ($arrayConvert as $kac=>$vac) {
            if (array_key_exists($vac, $dataField)) {
                if (array_key_exists($kac, $arrayType)) {
                    if (array_key_exists($arrayType[$kac], $arrayFunction)) {
                         $fields[$kac] = $arrayFunction[$arrayType[$kac]]($dataField[$vac]);
                    } else {
                        switch ($arrayType[$kac]) {
                            case 'int':
                                $fields[$kac] = (int)$dataField[$vac];
                                break;
                            case 'string':
                                $fields[$kac] = (string)$dataField[$vac];
                                break;
                            default:
                                $fields[$kac] = $dataField[$vac];
                                break;
                        }
                    }
                } else {
                    $fields[$kac] = $dataField[$vac];
                }
            }
        }
        return $fields;
    }
     /**
     * Sostituisce '#__' con il prefisso reale delle tabelle
     * @param   String		$query
     * @return  String 		Ritorna La query con i prefissi sostituiti
     */
    private function sqlPrefix($query) {
        return str_replace("#__", $this->prefix, $query);
    }

    /**
     * Close connection
     */
    function close() 
    {
        if($this->mysqli != null) {   
            $this->mysqli->close();
        }
    }
}
