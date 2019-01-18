<?php
/**
 * GP Listener
 * Serve per impostare degli eventi e richiamare i metodi delle classi che sono stati aggiunti a questi eventi
 * @package     	app
 * @subpackage		platform
 */

/**
 * GPListener
 * Serve per impostare degli eventi e richiamare i metodi delle classi che sono stati aggiunti a questi eventi 
*/
defined('_GPEXEC') or die;
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
	public function add($event, $obj, $method) {
		if (!array_key_exists($event, $this->listener)) {
			$this->listener[$event] = array();
		}
		if (method_exists($obj, $method)) {
			$this->listener[$event][] = array($obj, $method);
			return true;
		}
		return false;
	}
	/*
	* Rimuove un listener
	* Per rimuovere un listener bisogna passargli gli stessi dati che sono stati impostati quando è stato aggiunto.
	*/
	public function remove($event, $obj = "", $method = "") {
		if (array_key_exists($event, $this->listener)) {
			foreach ($this->listener[$event] as $k=>$l) {
				if (($l[0] == $obj || $obj == "") && ($l[1] == $method || $method == "")) {
					array_splice($this->listener[$event],$k,1);
					return true;
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
}

?>
