<?php
/**
 * Gli eventi 
 */
class GpListener
{
	/*
	 * @var 		GpListener  	L'istanza della classe per il singleton
	*/
	private static $instance 	= null;
	/*
	 * @var    listener
	*/
	private $listener = array();
	/**
	 * Ritorna il singleton della classe
	 * @return  	singleton GpListener
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
		$msgLog = (is_array($fn)) ? $fn[0]."->".$fn[1] : $fn;
		if (is_callable($fn)) {
			$this->listener[$event][] = $fn;
			Gp::log()->set('system', 'LISTENER', 'Add event '.$event.': function  '.$msgLog.' added');
			return true;
		} else {
			Gp::log()->set('system', 'ERROR', 'LISTENER add event '.$event.': function '.$msgLog.' is not callable');
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
					Gp::log()->set('system', 'LISTENER', 'Remove all event '.$event);
					unset($this->listener[$event]);
				} else if (is_array($fn)) {
					if ($l[0] == $fn[0] && $l[1] == $fn[1] ) {
						Gp::log()->set('system', 'LISTENER', 'Remove event '.$event.': function  '.$fn[0] .'->'. $fn[1] .' added');
						array_splice($this->listener[$event],$k,1);
						return true;
					}
				} else {
					if ($l == $fn ) {
						Gp::log()->set('system', 'LISTENER', 'Remove event '.$event.': function  '.$fn.' added');
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
				$msgLog = (is_array($l)) ? $l[0]."->".$l[1] : $l;
				Gp::log()->set('system', 'LISTENER', 'Invoke event '.$event.': function  '.$msgLog .' added');
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