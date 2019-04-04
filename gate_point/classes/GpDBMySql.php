<?php
/**
 * Classe gestione db Mysql 
 */
class GpDBMySql
{
     var $mysqli = null;
     var $error = false; // true se una query da un errore
     var $tablesList = array(); // l'elenco delle tabelle
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
        $mysqli_temp = new mysqli($ip, $login, $pass, $dbname);
        if ($mysqli_temp->connect_errno) 
        {
            $this->error =  true;
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
        if(!$this->checkError()) {  
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
        if(!$this->checkError()) {  
            return false;
        }
        $sql = $this->sqlPrefix($sql);
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
        if(!$this->checkError()) {  
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
     * @param String $tableName primary key
     * @param Boolean $cache default true
     * @return Array fields and key
     */
    function describes($tableName, $cache = true) {
        if(!$this->checkError()) {  
            return false;
        }
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
        if(!$this->checkError()) {  
            return false;
        }
        $sql = $this->sqlPrefix($sql);
        //print "<p>".$sql."</p>";
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
        if(!$this->checkError()) {  
            return false;
        }
        $sql = $this->sqlPrefix($sql);
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
        if(!$this->checkError()) {  
            return false;
        }
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
            $query = $this->sqlPrefix($query);
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
        if(!$this->checkError()) {  
            return false;
        }
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
        if(!$this->checkError()) {  
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
        if(!$this->checkError()) {  
            return false;
        }
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
        if($this->checkError()) {  
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
    function quoteName($val) {
        return '`'.$val.'`';
    }
    /**
     * Quote value
     *  @param String $val
     * @return String
     */
    function quote($val) {
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
        if($this->checkError()) {   
            $this->mysqli->close();
        }
    }

    function checkError() {
        if($this->mysqli == null) 
        {   
            $this->error = true;
            return false;
        }
        $this->error = false;
        return true;
    }
}