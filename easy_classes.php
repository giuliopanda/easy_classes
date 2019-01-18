<?php
/** 
 * BUILD 20190116 EASY_CASSES V.1
 */

/**
 * Classe gestione db Mysql 
 */
class gpDBMySql
{
     private $mysqli = null;
     var $error = false; // true se una query da un errore
     var $tablesList = array(); // l'elenco delle tabelle
     var $prefix = ''; // l'elenco delle tabelle
    /** 
    * COSTRUCTOR INIZIALIZZA LA CONNESSIONE
    */
    function __construct($ip, $login, $pass, $dbname) 
    {
        $mysqli_temp = new mysqli($ip, $login, $pass, $dbname);
        if ($mysqli_temp->connect_errno) 
        {
            $this->error =  true;
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
    function insertId() {
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
/**
 * Classe gestione Router
 */
class gpRouter
{
    var $dirRoot = "/"; // la directory del sito
    var $relativePath = "";  // il path in più per i siti che partono da una sottocartella o con htaccess
    var $scheme = "http";
    var $site = ""; // l'home del sito
    var $fnParse = null;
    var $fnBuild = null;
    static $instance;
    /**
	 * Ritorna il singleton della classe
	 * @return  	singleton
	**/
	public static function getInstance()
	{
    if(self::$instance == null)
    {
        $c = __CLASS__;
        self::$instance = new $c;
        
        self::$instance->setConfig();
    }
    return self::$instance;
    }
    /**
     * Configura manualmente la directory e i link
     * @param String $dirRoot La directory dov'è il sito
     * @param String $scheme http/https
     * @param String $site  L'url del sito (con eventuali subdomain)
     */
    function setConfig($dirRoot = "", $scheme = "", $site= "") {
        if ($dirRoot == "") {
            $backtrace = debug_backtrace();
            if (count( $backtrace ) > 0) {
                $dirRoot = dirname(array_pop($backtrace)['file']);
            }
        }
        if ($dirRoot == "") {
            $dirRoot = __DIR__;
        }
        $dirRoot = str_replace("\\","/",$dirRoot);
        $documentRoot = str_replace("\\","/",$_SERVER["DOCUMENT_ROOT"]);
       
        $this->relativePath = str_replace($documentRoot, "", $dirRoot);
        $this->dirRoot = $dirRoot;
        if ($scheme != "") {
            $this->scheme = $scheme;
        } else {
            $this->scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
        }
        if ($site != "") {
            $site = str_replace("\\","/", $site);
            $temp = explode("/", $site);
            if (count($temp) > 1) {
                array_shift($temp);
                $this->relativePath = implode("/", $temp);
            }
            $this->site = $site;
        } else {
            $this->site = $_SERVER[HTTP_HOST].$this->relativePath;
        }
    }
    /** 
     * Imposta delle funzioni per personalizzare il parsing dell'url
     */
    function setFnRewrite( $fnBuild, $fnParse) {
        $this->fnBuild = $fnBuild;
        $this->fnParse = $fnParse;
    }
    /** 
     * Ritorna il link corrente della pagina
     * @param Boolean $getUri Se avere le query oppure no
     * @return String
     */
    function getCurrentLink($getUri = true) {
        if ($getUri === true) {
            return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        }
        if ($getUri === false) {
            return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]".strtok($_SERVER["REQUEST_URI"],'?'); 
        }
    }
    /**
     * Ritorna la directory dov'è salvato il sito
     * @return String 
     */
    function getDir() {
        return $this->dirRoot."/";
    }
    /**
     * Ritorna l'home del sito 
     * @return String 
     */
    function getSite() {
        return $this->scheme . "://" . $this->site; 
    }
    /**
     * Ritorna un link completo
     * @param String $query il link ad esempio esempio/index.php?var=1
     * @return String 
     */
    function getLink($query = "") {
        $query = str_replace(array($this->scheme . "://", $this->site), '', $query);
        if ($this->fnBuild != null) {
            return $this->scheme . "://" . $this->site . call_user_func_array($this->fnBuild, array($query, $this));
        } 
        if ($query != "" && substr($query,0,1)!= "/") {
            $query = "/".$query;
        }
        return $this->scheme . "://" . $this->site . $query; 
    }
    /**
     * Fa il parsing di una url dove pathQuery sono i parametri del percorso  
     * Se il link è relativo devo metterci lo / davanti
     *@param String  $link 
     *@return Array ('scheme':string,'host':string, 'path':string,'filename':string,'pathQuery':array,'query':array)
     */
    function parseUrl($link = "") {
        if ($link == "") {
            $link = $this->getCurrentLink();
        }
        $ris = parse_url($link);
        if ($ris['path']) {
            //$ris['filename'] = basename($ris['path']);
            $risPath = $ris['path'];
            $ris['path'] = array_values (array_filter(explode("/",  str_replace($this->relativePath, '', $risPath))));
        }
        
        if ($ris['query']) {
            $ris['query'] = str_replace("&amp;", "&", $ris['query']);
            $temp = explode("&", $ris['query']);
            $t3 = array();
            foreach ($temp as $val) {
                $temp2 = explode("=", $val);
                $t3[$temp2[0]] = $temp2[1]; 
            }
            $ris['query'] = $t3;
        }
        if ($this->fnParse != null) {
            return call_user_func_array($this->fnParse, array($ris, $this));
        } 
        return $ris;
    }
    /**
     * Implode un'array per le query
     * @param Array $query
     * @return String
     */
    function implodeQuery($query = "") {
        if (is_array($query) && count($query) > 0) {
            $list = array();
            foreach ($query as $key=>$value) {
                $list[] = $key."=".$value;
            }
            return "?".implode("&", $list);
        }
        return "";
    }

}
/**
 * GPListener
 * Serve per impostare degli eventi e richiamare i metodi delle classi che sono stati aggiunti a questi eventi 
*/
class GPListener
{
	/*
	 * @var 		GPListener  	L'istanza della classe per il singleton
	*/
	private static $instance 	= null;
	/*
	 * @var    listener
	*/
	private $listener = array();
	/**
	 * Ritorna il singleton della classe
	 * @return  	singleton GPObserver
	**/
	public static function getInstance()
	{
   	   if(self::$instance == null)
	   {
   	      $c = __CLASS__;
   	      self::$instance = new $c;
		}
		return self::$instance;
   	}
	/*
	* Aggiunge un listener
	* Quando si aggiunte un listener verrà chiamato il metodo della classe passata in obj ogni volta che da qualsiasi parte sarà richiamata
	* la funzione invoke con lo stesso nome di evento.
	*/
	public function add($event, $fn) {
		if (!array_key_exists($event, $this->listener)) {
			$this->listener[$event] = array();
		}
		if (is_callable($fn)) {
			$this->listener[$event][] = $fn;
			return true;
		}
		return false;
	}
	/*
	* Rimuove un listener
	* Per rimuovere un listener bisogna passargli gli stessi dati che sono stati impostati quando è stato aggiunto.
	*/
	public function remove($event, $fn = false) {
		if (array_key_exists($event, $this->listener)) {
			foreach ($this->listener[$event] as $k=>$l) {
				if ($fn === false) {
					unset($this->listener[$event]);
				} else if (is_array($fn)) {
					if ($l[0] == $fn[0] && $l[1] == $fn[1] ) {
						array_splice($this->listener[$event],$k,1);
						return true;
					}
				} else {
					if ($l == $fn ) {
						array_splice($this->listener[$event],$k,1);
						return true;
					}
				}
			}
		}
		return false;
	}
	/*
	* Richiama tutti i listener impostati per quel determinato evento
	* Invoke ha due parametri obbligatori, ma accetta n parametri in base all'accordo con il listener. I metodi richiamati dal listener sovrascrivono sempre e solo il primo parametro. Allo stesso modo la funzione ritorna solo il terzo parametro elaborato dai listener.
	*/
	public function invoke($event) {
		$args = func_get_args();
		array_shift($args);
		if (!array_key_exists(0, $args)) {
			$args[0] = FALSE;
		}
		if (array_key_exists($event, $this->listener)) {
			foreach ($this->listener[$event] as $l) {
				$args[0] = call_user_func_array($l, $args);
			}
			return $args[0];
		}
		return $args[0];
	}
	/*
	* Richiama tutti i listener impostati per quel determinato evento
	* Invoke ha due parametri obbligatori, ma accetta n parametri in base all'accordo con il listener. I metodi richiamati dal listener sovrascrivono sempre e solo il primo parametro. Allo stesso modo la funzione ritorna solo il terzo parametro elaborato dai listener.
	*/
	public function showEvents($event) {
		$args = func_get_args();
		$fns = array();
		if (array_key_exists($event, $this->listener)) {
			foreach ($this->listener[$event] as $l) {
				if (is_array($l)) {
					$fns[] = get_class($l[0]).":".$l[1];
				} else {
					$fns[] =  $l;
				}
			}
			return $fns;
		}
		return false;
	}
}
/**
 * GPRegistry
 * Memorizza e restituisce dati passati tra le classi, in sessione o attraverso le query dell'url.
*/
class GPRegistry
{
	/*
	 * @var 		GPRegistry  	L'istanza della classe per il singleton
	*/
	private static $instance 	= null;
	/*
	 * @var 		array  			Tutti i dati del Registro
	*/
	var $registry 				= array();
	/**
	 * Il costruttore della classe
	 * @return  	void
	**/
	function __construct() 
	{
		if ($_SESSION && !array_key_exists('_gpregistry',$_SESSION)) {
			$_SESSION['_gpregistry'] = array();
		}
	}
	/**
	 * Ritorna il singleton della classe
	 * @return  	singleton
	**/
	public static function getInstance() 
	{
   	   if(self::$instance == null) 
	   {   
   	      $c = __CLASS__;
   	      self::$instance = new $c;
		}
		return self::$instance;
   	}
	/**
	 * Memorizza una nuova variabile. Se si vuole salvare su sessione il path sarà session.path . 
	 * Se si vuole fare l'override dei request il path sarà request.miopath (esempio request.option).
	 * Per rimuovere una variabile basta passare al path il varole NULL.	
	 * @param   data		mixed		I dati da salvare
	 * @param   path		string		Dove salvare i dati all'intero dell'array registry es: main.mieidati.
	 * @return  	void
	**/
	function set($path, $data) 
	{
		if (is_object($data)) {
 			$data = (array) $data;
		}
		if ($path != "") {
 			$path = explode(".", $path);
			if (@$path[0] == "session") {
				$pointer = &$_SESSION['_gpregistry'];
			} else {
				$pointer = &$this->registry;
			}
			foreach ($path as $k=>$p) {
				$p = trim($p);
				if ($p == "[]") {
					if (!is_array($pointer)) {
						$pointer = array($data);
					} else {
						$pointer[] = $data;
					}
					return;
				} else {
					if (!isset($pointer[$p])) {
						$pointer[$p] = array();
					}
					if ($k == count($path)-1 && $data === NULL) {
						unset($pointer[$p]);
						return;
					} else {
						$pointer = &$pointer[$p];
					}
				}
			}
			$pointer = $data;	
		}
	}
	/**
	 * Ritorna una variabile. 
	 *@param   path		string		Il percorso in cui sono stati memorizzati i dati all'intero dell'array registry es: main.miavar
	 *@param   default	mixed	Se non ci sono dati nel path ritorna il valore impostato
	 *@param   return  	mixed
	**/
	function get($path = "main", $default = false) 
	{
		if ($path != "") {
			$path = explode(".", $path);
			if (@strtolower(trim($path[0])) == "session") {
				$pointer = &$_SESSION['_gpregistry'];
			} elseif (@strtolower(trim($path[0])) == "request") {
				$pointer = $this->getRequest();
				array_shift($path);
			} else {
				$pointer = &$this->registry;
			}
			foreach ($path as $p) {
				$p = trim($p);
				if (!array_key_exists($p, $pointer) || @$pointer[$p] === '') {
					return $default;
				}
				$pointer = &$pointer[$p];
			}
			return $pointer;
		} else {
			return NULL;
		} 	
	}
	/**
	 * $_REQUEST del php include anche i cookie, questa versione no.
	 * In aggiunta getRequest trasforma i true e false in boolean e tutto minuscolo
	 *@return array
	**/
	private function getRequest() {
		if (@is_array($_GET) && @is_array($_POST)) {
			$return = ($_GET + $_POST);
		} elseif (@is_array($_GET)) {	
			$return = $_GET;
		} elseif (@is_array($_POST)) {
			$return =  $_POST;
		} else {
			$return = array();
		}
		if (@is_array($this->registry['request'])) {
			$return = ($this->registry['request'] + $return );
		}
		$ris = array();
		foreach ($return as $key=>$value) {
			if (is_string($value)) {
				if (trim($value) == "true") $value = true;
				if (trim($value) == "false") $value = false;
			}
			$ris[trim(strtolower($key))] = $value;
		}
		return $ris;
	}
	/** 
	 * Carica i dati dal $this->getRequest(); e se esistono li salva in sessione. Se non esistono cerca i dati nella sessione.
	 * Se i dati nella sessione non esistono salva in sessione i dati del default. 
	 *@param   path			string		Il percorso in cui sono stati memorizzati i dati.
	 *@param   default		mixed		Se non ci sono dati nel path ritorna il valore impostato
	 *@return		mixed
	*/
	function requestQueue($path, $default = "") 
	{
		if ($path == "")  {
			$path = $this->get('request');
		}
		if (!is_string($path) || $path == "") {
			$path = "main";
		}
		$requestValue = $this->get("request.".$path, NULL);
		$sessionValue = $this->get("session.".$path, NULL);
		
		if ($requestValue !== NULL) {
			$this->set( "session.".$path, $requestValue);
			return $requestValue;
		} elseif ($sessionValue !== NULL) {
			return $sessionValue;
		} else {
			$this->set("session.".$path, $default);
			return $default;
		}
		
	}
	/** 
	 * Cancella i dati della sessione usati da GPRegistry
	 * @return void 
	*/
	function clearQueue() 
	{
		unset($_SESSION['_gpregistry']);
	}	
}