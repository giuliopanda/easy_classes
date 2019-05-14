<?php
/**
 * SERVE PER CARICARE LE VARIE PARTI DEL SITO E FARE EVENTUALI OVERRIDE
 */
class GpLoad
{
    /*
	 * @var 		GpLoad  	L'istanza della classe per il singleton
	*/
	private static $instance 	= null;
 	/*
	 * @var 		Class  	Le istanze delle classi per i moduli
	*/
	private static $modulesClass	= array();

	/*
	 * @var    link     I link del sito
	*/
    public $link = array();
    /*
	 * @var    dir      Le directory del sito
	*/
	public $dir = array();
	/**
	 * Ritorna il singleton della classe
	 * @return  	singleton GpLoad
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
	 * Imposta una directory
	 * @param String $varName
	 * @param String|Array $path il percorso relativo dalla directory principale senza lo slash finale
	 */
	public function setPath($varName, $path) {
		$rooter = GpRouter::getInstance();
		
		if (is_string($path)) {
			$path = array($path);
		}
		$this->dir[$varName] = $path;
				
	}
	/**
	 * Verifica se un percorso è stato impostato oppure no.
	 * @param String $varName
	 * @param String|Array $path il percorso relativo dalla directory principale senza lo slash finale
	 */
	public function issetPath($varName) {
		return (array_key_exists($varName, $this->dir));
	}
	/**
	 * Ritorna il link per le risorse
	 * @param String $varName
	 * @param String|Array $fileName 
	 */
	public function getUri($varName = "", $fileName = "") {
		if ($varName == "") {
			return $rooter->getSite();
		}
		$rooter = GpRouter::getInstance();
		$dir = $rooter->getDir();
		if (array_key_exists($varName, $this->dir)) {
			if ($fileName == "") {
				foreach ($this->dir[$varName] as $path ) {
					if (is_dir($dir.$path)) {
						return ($rooter->getSite()."/".$path);
					}
				}
			} else {
				if (!is_array($fileName)) {
					$fileName = array($fileName);
				}
				foreach ($fileName as $fname) {
					foreach ($this->dir[$varName]  as $path ) {
						if (is_file($dir.$path."/".$fname)) {
							return ($rooter->getSite()."/".$path."/".$fname);
						}
					}
				}
			}
		
		} else {
			return '';
		}
	}
	/**
	 * Ritorna il percorso del file se esiste, o la prima directory esistente
	 * @param String $varName il nome del gruppo di directory da richiamare 
	 * @param String $fileName Il nome del file da richiamare
	 * @return Mixed String|false
	 */
	public function getPath($varName = "", $fileName = "") {
		$rooter = GpRouter::getInstance();
		$dir = $rooter->getDir();
		if ($varName == "") {
			return $dir;
		}
		if (array_key_exists($varName, $this->dir)) {
			if ($fileName == "") {
				foreach ($this->dir[$varName] as $path ) {
					if (is_dir($dir.$path)) {
						return ($dir.$path);
					}
				}
			} else {
				if (!is_array($fileName)) {
					$fileName = array($fileName);
				}
				foreach ($fileName as $fname) {
					foreach ($this->dir[$varName]  as $path ) {
						if (is_file($dir.$path."/".$fname)) {
							return ($dir.$path."/".$fname);
						}
					}
				}
			}
		} 
		return false;
	}
	/** 
	 * appende ad un percorso un nuovo segmento 
	 * @param String $newName il nome del nuovo percorso
	 * @param String $varName Il nome del percorso da cui partire
	 * @param String $appendPath il segmento di directory da aggiungere
	 */
	public function append($newName, $varName, $appendPath) {
		$rooter = GpRouter::getInstance();
		$dir = $rooter->getDir();
		$newPath = array();
		if (array_key_exists($varName, $this->dir)) {
			foreach ($this->dir[$varName] as $path ) {
				$newPath[] = str_replace("//","/", $path."/".$appendPath);
			}
		}
		$this->setPath($newName, $newPath); 
	}
	/** 
	 * Ritornano le configurazioni impostate di una url 
	 * @param String $varName il nome del gruppo di directory da richiamare 
	 * @param Boolean $override Se deve tornare il path principale o l'override (se true). Di default false
	 * @return String
	 */
	public function get($varName) {
		if (array_key_exists($varName, $this->dir)) {
			return $this->dir[$varName];
		}
		return false;
	}
	/**
	 * Esegue il require di un file php
	 * @param String $varName il nome del gruppo di directory da richiamare 
	 * @param String|Array $fileName Il nome del file da richiamare
	 * @param Mixed $data Il path di GpRegistry oppure un array o un oggetto
	 * @param Boolean $requireOnce Se usare require_once o require
	 * @param String $variable Il nome della variabile in cui settare i dati. di default i dati sono settati in $cData
	 * @return Boolean
	 */
	public function require($varName, $fileName = "", $data = false, $requireOnce = false, $variable = false) {
		if ($fileName == "") {
			if (is_file($varName)) {
				$path = $varName;
			} else {
				$path = false;
			}
		} else {
			$path = $this->getPath($varName, $fileName);
		}
		
		if(!is_bool($data) && is_object($data) && get_class($data) == "GpRegistry") {
			$cData = $data;
		} else {
			$cData = new GpRegistry();
			if ($data != false) {
				if (is_object($data)) {
					$cData->registry = (array)$data;
				} else if (is_array($data)) {
					$cData->registry = $data;
				} else if (is_string($data)) {
					$cData->registry = GpRegistry::getInstance()->get($data);
				} 
			}
		}
		if ($variable != false) {
			$$variable = $cData;
		}
		
		if ($path) {
			if (!is_file($path)) {
				Gp::log()->set('error', 'REQUIRE', $path);
				Gp::log()->set('system', 'ERROR', 'Require: '.$path);
			}
			Gp::log()->cleanPointerHTML();
			Gp::log()->set('system', 'LOAD', 'Require: '.$path);
			if ($requireOnce) {
				require_once($path);
			} else {
				require($path);
			}
			return true;
		} else {
			if (!array_key_exists($varName, $this->dir)) {
				Gp::log()->set('error', 'REQUIRE path ', $varName." No exist");
				Gp::log()->set('system', 'ERROR', 'Require path '.$varName." No exist");
			} else {
				$pathList = $this->get($varName);
				if (!is_array($pathList)) {
					$pathList = array($pathList);
				}
				Gp::log()->set('error', 'REQUIRE ', $varName.":".implode("# ",$pathList));
				Gp::log()->set('system', 'ERROR', 'Require '.$varName.": ".implode("# ",$pathList));
			}
		}
		return false;
	}
	/**
	 * Esegue un modulo
	 * @param String $moduleName il nome del gruppo di directory da richiamare 
	 * @param String $action L'azione passata al modulo
	 * @param Mixed $data Il path di GpRegistry oppure un array o un oggetto
	 * @return Boolean
	 */
	public function module($moduleName, $method = "", $data = false) {
		if (!array_key_exists("modules", $this->dir)) {
			$this->setPath("modules", "modules");
		}
		$path = $this->getPath("modules", $moduleName."/".$moduleName.".php");

		if ($data != false) {
			if (is_string($data)) {
				$data = GpRegistry::getInstance()->get($data);
			} 
			if (is_object($data)) {
				$data = (array)$data;
			} else if (is_array($data)) {
				$data = $data;
			} 
		} else {
			$data = array();
		}
		$debug = (debug_backtrace());
		if (count ($debug) > 0) {
			$debug = array_shift($debug);
		}
		Gp::log()->setPointerHTML();
		if ($path) {
			$className = "module_".str_replace(".","_",$moduleName);
			if(!array_key_exists($moduleName, self::$modulesClass)) {				
				require_once($path);		
				if (class_exists($className)) {
					self::$modulesClass[$moduleName] = new $className;
				} else {
					Gp::log()->set('error', 'LOAD', ' Module: '.$moduleName.": class ".$className. " not exists in ".$path);
					Gp::log()->set('system', 'ERROR', ' Module: '.$moduleName.": class ".$className. " not exists in ".$path);
					return false;
				}
			}
			if ($method == "") {
				Gp::log()->set('system', 'LOAD', ' Class: '.$moduleName);
				// ritorna l'istanza della classe
				return self::$modulesClass[$moduleName];
			}
			
			if (method_exists(self::$modulesClass[$moduleName], $method )) {
				Gp::log()->set('system', 'LOAD', 'Module '.$moduleName." function: ".$method);
				$reflection = new ReflectionMethod($className, $method);
				$fire_args = array();
				if ($reflection) {
					foreach($reflection->getParameters() AS $arg)
					{
						if(array_key_exists($arg->name, $data)) {
							$fire_args[$arg->name] = $data[$arg->name];
						} else {
							$fire_args[$arg->name] = false;
						}
					}
				}
				$ris = call_user_func_array(array(self::$modulesClass[$moduleName], $method), $fire_args);
				$ris = Gp::action()->invoke($className."_event", $ris,  $method, $data);
				return $ris;
			} else {
				Gp::log()->set('error', 'LOAD', 'Module: '.$moduleName." function ".$method.":  method not exist ");
				Gp::log()->set('system', 'ERROR', 'LOAD Module: '.$moduleName." function ".$method.":  method not exist ");
			}
		} else {
			Gp::log()->set('error', 'LOAD', "Module: ".$moduleName." path non found");
			Gp::log()->set('system', 'ERROR', 'LOAD Module: '.$moduleName." path non found");
		}
		return false;
	}
}
