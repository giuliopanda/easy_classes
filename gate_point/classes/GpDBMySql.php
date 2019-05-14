<?php
/**
 * Classe gestione db Mysql 
 */
class GpDBMySql
{
     var $mysqli = null;
     var $error = false; // true se una query da un errore
     var $tablesList = array(); // l'elenco delle tabelle
     var $fieldsList = array(); //l'elenco dei campi per tabella
     var $prefix = ''; // l'elenco delle tabelle
    static $instance;
    /**
	 * Ritorna il singleton della classe
	 * @return  	singleton GpDBMySql
	**/
    public static function getInstance() {
        if(self::$instance == null) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }
    /** 
    * COSTRUCTOR INIZIALIZZA LA CONNESSIONE
    */
    function __construct($ip = "", $login = "", $pass = "", $dbname = "") {
        if ($ip != "" && $dbname != "") {
            $this->connect($ip, $login, $pass, $dbname);
        }
    }
    /**
     * Configura manualmente la directory e i link
     * @param String $dirRoot La directory dov'è il sito
     * @param String $scheme http/https
     * @param String $site  L'url del sito (con eventuali subdomain)
     */
    function connect($ip, $login, $pass, $dbname) {
        $this->close();
        $this->error =  false;;
        $mysqli_temp = new mysqli($ip, $login, $pass, $dbname);
        if ($mysqli_temp->connect_errno) 
        {
            $this->error =  true;
            Gp::log()->set('error', 'MYSQL', "Connect. ip:".$ip." login:". $login." psw:".$pass." dbname:".$dbname);
			Gp::log()->set('system', 'ERROR', "MYSQL Connect. ip:".$ip." login:". $login." psw:".$pass." dbname:".$dbname);
            return false;
        } else {
            $this->mysqli = $mysqli_temp;
            return true;
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
        if(!$this->checkConnection()) {  
            return false;
        }
        $sql = $this->sqlPrefix($sql);
        $ris = $this->mysqli->query($sql);
        if ($ris === false)  {
            Gp::log()->set('system', 'ERROR', "MYSQL Query: '".$sql."' error:". $this->mysqli->error);
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
        if (!$result = $this->query($sql)) {
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
        if(!$this->checkConnection()) {  
            return false;
        }
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
     * @param String $tableName il nome della tabella
     * @param Boolean $cache default true
     * @return Array fields and key
     */
    function describes($tableName, $cache = true) {
        if(!$this->checkConnection()) {  
            return false;
        }
        $ris = array();
         if ($cache && array_key_exists($tableName, $this->fieldsList) != false) {
            return $this->fieldsList[$tableName];
        }
        $fields = $this->mysqli->query('DESCRIBE '.$this->qn($tableName)); 
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
     * Setta l'elenco dei campi di una tabella così non deve fare la query per il describe
     * @param String $tableName il nome della tabella
     * @param Array $fields l'array dei campi es: ('id' =>'int(11)', 'username' => 'varchar(250)')
     * @param String $primaryKey il nome del campo della primary key
     */
    function setFieldsList($tableName, $fields, $primaryKey) {
        $this->fieldsList[$tableName] = array('fields'=>$fields, 'key'=>$primaryKey);
    }

    /**
     * Esegue una query Select e restituisce la prima riga
     * @param String $sql 
     * @param Integer $offset Default 0, Sceglie la riga da scaricare
     * @return Array
     */
    function getRow($sql, $offset = 0) 
    {
        if (!$result = $this->query($sql)) {
            return false;
        }
        $k = 0;
        while($row = mysqli_fetch_assoc($result)) {
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

        if (!$result = $this->query($sql)) {
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
        if(!$this->checkConnection()) {  
            return false;
        }
        $field = array();
        $values = array();
        $tableFields = $this->describes($table);
        foreach ($data as $key=>$val) 
        {
            if (array_key_exists($key, $tableFields['fields'])) {
                $field[] = $this->qn($key);
                $values[] = $this->q($val);
            }
        }
        if (count($values) > 0) 
        {
            $query = "INSERT INTO ".$this->qn($table)." (".implode(", ", $field)." ) VALUES (".implode(", ", $values).");";
            $query = $this->sqlPrefix($query);
            $resQuery = $this->query($query);
            if (!$this->error) {
                return $this->mysqli->insert_id;
            } else {
                $this->error = true;
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
        if(!$this->checkConnection()) {  
            return false;
        }
        $values = array();
        $tableFields = $this->describes($table);
        foreach ($where as $key=>$val) {
            if (array_key_exists($key, $tableFields['fields'])) {
                $values[] = $this->qn($key)." = ". $this->q($val);
            } else {
                $this->error = true;
                return false;
            }
        }
        if (count($values) > 0) {
            $query = "DELETE FROM ".$this->qn($table)." WHERE ".implode(", ", $values).";";
            $query = $this->sqlPrefix($query);
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
        if(!$this->checkConnection()) {  
            return false;
        }
        $sql = $this->sqlPrefix($sql);
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
        if(!$this->checkConnection()) {  
            return false;
        }
        $this->error = false;
        $field = array();
        $values = array();
        $tableFields = $this->describes($table);
        foreach ($data as $key=>$val) 
        {
            if (array_key_exists($key, $tableFields['fields'])) {
                $field[] = $this->qn($key)." = ". $this->q($val);
            }
        }
        foreach ($where as $key=>$val) 
        {
            if (array_key_exists($key, $tableFields['fields'])) {
                $values[] = $this->qn($key)." = ". $this->q($val);
            } else {
                $this->error = true;
                return false;
            }
        }
        if (count($values) > 0) 
        {
            $query = "UPDATE ".$this->qn($table)." SET ".implode(", ", $field)."  WHERE ".implode(", ", $values).";";
            $query = $this->sqlPrefix($query);
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
    function insertId() {
        if($this->checkConnection()) {  
            return $this->mysqli->insert_id;
        } else {
            return false;
        }
    } 
    /**
     * QUOTE name or table
     * @param String $val
     * @return String
     */
    function qn($val) {
        $val = $this->sqlPrefix($val);
        return '`'.$val.'`';
    }
    /**
     * Quote value
     *  @param String $val
     * @return String
     */

    function q($val) {
        $val = $this->sqlPrefix($val);
        if($this->mysqli != null)  {
            return "'".$this->mysqli->real_escape_string($val)."'";
        } else {
            return $val;
        }
    }
     /**
     * Sostituisce '#__' con il prefisso reale delle tabelle
     * @param   String		$query
     * @return  String 		Ritorna La query con i prefissi sostituiti
     */
    private function sqlPrefix($query) {
        return str_replace("#__", $this->prefix."_", $query);
    }
    /**
     * Close connection
     */
    function close() 
    {
        if($this->checkConnection()) {   
            $this->mysqli->close();
        }
    }

    function checkConnection() {
        if($this->mysqli == null) {   
            $this->error = true;
            return false;
        }
        $this->error = false;
        return true;
    }
}