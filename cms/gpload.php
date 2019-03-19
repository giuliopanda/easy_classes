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
    private $link = array();
    /*
	 * @var    dir      Le directory del sito
	*/
	private $dir = array();
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
	 * @param String $defaultFilename
	 */
	public function setPath($varName, $path, $override = "", $defaultFilename = "") {
		$rooter = gpRouter::getInstance();
		$dir = $rooter->getDir();
		$this->dir[$varName] = array("path"=>$dir.$path, "defaultFilename"=>$defaultFilename, 'uri'=>$path);
		if ($override != "" && is_dir($dir.$override)) {
			$this->dir[$varName]["override"] =$dir.$override;
		}
		if ($defaultFilename != "") {
			$this->dir[$varName]["defaultFilename"] =$defaultFilename;
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
	public function getPath($varName, $fileName) {
		if (array_key_exists($varName, $this->dir)) {
			if (array_key_exists('override', $this->dir[$varName]) && $this->dir[$varName]['override'] != "") {
				if (is_file($this->dir[$varName]['override']."/".$fileName)) {
					return ($this->dir[$varName]['override']."/".$fileName);
				}
			} 
			if (is_file($this->dir[$varName]['path']."/".$fileName)) {
				return ($this->dir[$varName]['path']."/".$fileName);
			}

			if (array_key_exists('override', $this->dir[$varName]) && $this->dir[$varName]['override'] != "" && array_key_exists('defaultFilename', $this->dir[$varName]) && $this->dir[$varName]['defaultFilename'] != "") {
				if (is_file($this->dir[$varName]['override']."/".$this->dir[$varName]['defaultFilename'])) {
					return ($this->dir[$varName]['override']."/".$this->dir[$varName]['defaultFilename']);
				}
			}
			if (array_key_exists('defaultFilename', $this->dir[$varName]) && $this->dir[$varName]['defaultFilename'] != "") { 
				if (is_file($this->dir[$varName]['path']."/".$this->dir[$varName]['defaultFilename'])) {
					return ($this->dir[$varName]['path']."/".$this->dir[$varName]['defaultFilename']);
				}
			}
		} 
		return false;
	}
	/**
	 * Esegue il require di un file php
	 * @param String $varName il nome del gruppo di directory da richiamare 
	 * @param String $fileName Il nome del file da richiamare
	 * @return Boolean
	 */
	public function require($varName, $fileName) {
		$path = $this->getPath($varName, $fileName);
		if ($path) {
			require($path);
			return true;
		}
		return false;
	}
	/**
	 * Esegue il require_once di un file php
	 * @param String $varName il nome del gruppo di directory da richiamare 
	 * @param String $fileName Il nome del file da richiamare
	 * @return Boolean
	 */
	public function require_once($varName, $fileName) {
		$path = $this->getPath($varName, $fileName);
		if ($path) {
			require_once($path);
			return true;
		}
		return false;
	}
}
