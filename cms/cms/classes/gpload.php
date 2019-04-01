<?php
/**
 * SERVE PER CARICARE LE VARIE PARTI DEL SITO E FARE EVENTUALI OVERRIDE
 */
class GPLoad
{
   /*
	 * @var 		GPLoad  	L'istanza della classe per il singleton
	*/
	private static $instance 	= null;
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
	 * @return  	singleton GPLoad
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
	 * @param String $path il percorso relativo dalla directory principale senza lo slash finale
	 * @param String $override il percorso relativo dalla directory principale senza lo slash finale
	 */
	public function setPath($varName, $path, $override = "") {
		$rooter = gpRouter::getInstance();
		$dir = $rooter->getDir();
		$this->dir[$varName] = array("path"=>$dir.$path,  'uri'=>$path);
		if ($override != "" && is_dir($dir.$override)) {
			$this->dir[$varName]["override"] =$dir.$override;
			$this->dir[$varName]["uriOverride"] = $override;
		}
		
	}
	/**
	 * Ritorna il link per per le risorse
	 * @param String $varName
	 */
	public function getUri($varName) {
		$rooter = gpRouter::getInstance();
		if (array_key_exists($varName, $this->dir)) {
			return $rooter->getSite()."/".$this->dir[$varName]['uri'];
		} else {
			return $rooter->getSite();
		}
	}
	/**
	 * Ritorna la directory e il nome del file da cui caricare il file
	 * @param String $varName il nome del gruppo di directory da richiamare 
	 * @param String $fileName Il nome del file da richiamare
	 * @return Mixed String|false
	 */
	public function getPath($varName, $fileName = "") {
		if (array_key_exists($varName, $this->dir)) {
			if ($fileName == "") {
				if (is_dir($this->dir[$varName]['path'])) {
					return ($this->dir[$varName]['path']);
				}
			} else {
				if (array_key_exists('override', $this->dir[$varName]) && $this->dir[$varName]['override'] != "") {
					if (is_file($this->dir[$varName]['override']."/".$fileName)) {
						return ($this->dir[$varName]['override']."/".$fileName);
					}
				} 
				if (is_file($this->dir[$varName]['path']."/".$fileName)) {
					return ($this->dir[$varName]['path']."/".$fileName);
				}
			}
			

		} 
		return false;
	}
	/**
	 * Ritornano le configurazioni impostate di una url 
	 * @param String $varName il nome del gruppo di directory da richiamare 
	 * @param Boolean $override Se deve tornare il path principale o l'override (se true). Di default false
	 * @return String
	 */
	public function get($varName, $override = false) {
		if (array_key_exists($varName, $this->dir)) {
			if ($override && array_key_exists('uriOverride', $this->dir[$varName])) {
				return ($this->dir[$varName]['uri']);
			} else {
				return ($this->dir[$varName]['uri']);
			}
		} 
		return '';
	}
	/**
	 * Esegue il require di un file php
	 * @param String $varName il nome del gruppo di directory da richiamare 
	 * @param String $fileName Il nome del file da richiamare
	 * @param Mixed $data Il path di gpRegistry oppure un array o un oggetto
	 * @param Boolean $requireOnce Se usare require_once o require
	 * @param String $variable Il nome della variabile in cui settare i dati. di default i dati sono settati in $this->cData
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
		$this->cData = new GPRegistry();
		if ($data != false) {
			if (is_object($data)) {
				$this->cData->registry = (array)$data;
			} else if (is_array($data)) {
				$this->cData->registry = $data;
			} else if (is_string($data)) {
				$this->cData->registry = GPRegistry::getInstance()->get($data);
			} 
		}
		if ($variable != false) {
			$$variable = $this->cData;
		}
		
		if ($path) {
			if ($requireOnce) {
				require_once($path);
			} else {
				require($path);
			}
			return true;
		}
		return false;
	}

	/**
	 * Esegue un modulo
	 * @param String $moduleName il nome del gruppo di directory da richiamare 
	 * @param String $returnType Il tipo di elemento che si vuole che ritorno (html, array)
	 * @param Mixed $data Il path di gpRegistry oppure un array o un oggetto
	 * @return Boolean
	 */
	public function module($moduleName, $returnType = "html", $data = false) {
		$path = $this->getPath("_modules", $moduleName."/".$moduleName.".php");
		if ($path == false) {
			$this->setPath("_modules", "modules");
			$path = $this->getPath("_modules", $moduleName."/".$moduleName.".php");
		}

		$cData = new GPRegistry();
		if ($data != false) {
			if (is_object($data)) {
				$cData->registry = (array)$data;
			} else if (is_array($data)) {
				$cData->registry = $data;
			} else if (is_string($data)) {
				$cData->registry = GPRegistry::getInstance()->get($data);
			} 
		}
		$listener = GPListener::getInstance();
		if ($path) {
			require_once($path);		
			$fn = "module_".$moduleName;
			if (is_callable($fn)) {
				$ris =  $fn($cData, $returnType);
				$ris = $listener->invoke($fn."_event", $ris, $cData, $returnType);
				return $ris;
			}
		}
		return false;
	}
}
