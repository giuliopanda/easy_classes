<?php 
/**
 * GP Registry
 * Memorizza e restituisce dati passati tra le classi, in sessione o attraverso le query dell'url.
 */
defined('_GPEXEC') or die;
/**
 * GPRegistry
 * Memorizza e restituisce dati passati tra le classi, in sessione o attraverso le query dell'url.
 * @package     	app
 * @subpackage		platform
 * @example 		../test-app/index.php
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
		if (!array_key_exists('_gpregistry',$_SESSION)) {
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
	function set($data, $path = "main") 
	{
		if ($path != "") {
			$path = explode(".", $path);
			if (@$path[0] == "session") {
				$pointer = &$_SESSION['_gpregistry'];
			} else {
				$pointer = &$this->registry;
			}
			foreach ($path as $k=>$p) {
				$p = strtolower(trim($p));
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
				$p = strtolower(trim($p));
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
	
	/*
	 * Carica i dati dal $this->getRequest(); e se esistono li salva in sessione. Se non esistono cerca i dati nella sessione.
	 * Se i dati nella sessione non esistono salva in sessione i dati del default. 
	 *@param   path			string		Il percorso in cui sono stati memorizzati i dati.
	 *@param   default		mixed		Se non ci sono dati nel path ritorna il valore impostato
	 *@param   return		mixed
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
			$this->set($requestValue, "session.".$path);
			return $requestValue;
		} elseif ($sessionValue !== NULL) {
			return $sessionValue;
		} else {
			$this->set($default, "session.".$path);
			return $default;
		}
		
	}
	
	/*
	 * Cancella i dati della sessione usati da GPRegistry
	 * @return void 
	*/
	function clearQueue() 
	{
		unset($_SESSION['_gpregistry']);
	}	
}
?>