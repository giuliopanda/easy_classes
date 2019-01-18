<?php
/**
 * GP URLParser
 * Esegue le principali query per la gestione di una tabella
 */
defined('_GPEXEC') or die;
/**
 * GPURLParser
 * Gestisce il parsing di un indirizzo internet
 * @package     	app
 * @subpackage		platform
 * @example 		../test-app/index.php
*/

class GPURLParser
{
	/*
	* @var array 		Tutti i dati del parsing dell'url
	*/
	public $url = array();
	/*
	 * @var 		string	  extension l'estensione a fine path es: .html
	*/
	public $extension = "";
	/**
	 * Il costruttore della classe
	 * @param 	string	$base  	l'indirizzo dell'home page
	 * @param 	string	$url  	L'indirizzo internet da elaborare - non c'è bisogno di ripetere l'indirizzo di base
	 * @return  void
	**/
	function __construct($url = "") {
		$set = GPConfig::get('setting');
		$this->extension = str_replace(".", '', $set['url']['extension']);
		if ($url != '') {
			$this->parse($url);
		}
   }
    /*
     * Ritorna la pagina corrente
	 * @return string
     */
	function currentPage() {
	 $pageURL = 'http';
	 if (@$_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	 $pageURL .= "://";
	 if (@$_SERVER["SERVER_PORT"] != "80") {
	  $pageURL .= @$_SERVER["SERVER_NAME"].":".@$_SERVER["SERVER_PORT"].@$_SERVER["REQUEST_URI"];
	 } else {
	  $pageURL .= @$_SERVER["SERVER_NAME"].@$_SERVER["REQUEST_URI"];
	 }
	 return $pageURL;
	}
	/*
	 * Fa il parsinge dell'url
	 * @param 	string 	$url  	L'indirizzo internet da elaborare - non c'è bisogno di ripetere l'indirizzo di base
	 * @return  void
	 */
	public function parse($url = 'base') {
		if ($this->extension != "") {
			$url = str_replace(".".$this->extension, "", $url);
		}
		$url = str_replace('\\','/', $url);
		$this->url['scheme']= "";
		$this->url['host'] = "";
		$this->url['queries'] = array();
		$this->url['dirname'] = array();
		$this->url['basename'] = "";
		$registry = GPRegistry::getInstance();
		$base = $_SERVER["SERVER_NAME"] . $registry->get('url.base', "/");

		if ($url == "current") {
			$url = $this->currentPage();
		} else if ($url == "base") {
			$url =  $base ;
		} elseif ((strpos($url, 'http') === false || strpos($url, 'http') > 2 )  && (strpos($url, 'www.') === false || strpos($url, 'www.') > 10) ) {
			$tempBase = str_replace(array("http://","https://", "www."), "", $base );
			$tempUrl = str_replace(array("http://","https://", "www."), "", $url );
			if (strpos($tempUrl, $tempBase ) === false) {
				if (substr($base,-1,1) == "/") {
					$url =  $base .$url ;
				} else {
					$url =  $base ."/".$url ;
				}
			}
		}
		if (strpos($url, 'http://') === false ) {
			$url =  "http://".$url ;
		}

		if ($url == "") {
			return;
		}
		$this->cleanUrl($url);
		$this->url = parse_url($url);

		if (!@$this->url['scheme']) {
			$this->url['scheme'] = "http";
		}
		$this->url['queries'] = array();
		$this->url['dirname'] = array();
		$this->url['basename'] = "";
		if (@$this->url['query'] != '') {
			$this->addQuery($this->url['query']);
		}
		if (@$this->url['path'] != '') {
			$this->url['basename'] = basename($this->url['path']);
			if (strpos(str_replace(GPATH_BASENAME, "", $this->url['basename']), ".") === false) {
				$this->url['dirname'] = $this->url['path'];
				$this->url['basename'] = "";
			} else {
				$this->url['dirname'] = dirname($this->url['path']);
			}
			if (in_array($this->url['dirname'], array(".","/","\\"))) {
				$this->url['dirname'] = "";
			}
			if (@$this->url['dirname']) {
				$this->url['dirname'] = explode("/", $this->url['dirname']);
				$this->url['dirname'] = array_filter($this->url['dirname']);
			} else {
				$this->url['dirname'] = array();
			}
		}
	}
	/*
	 * Pulisce un indirizzo internet da eventuali doppi slash o imprecisioni.
	 * Attenzione ritorna l'url non codificato con urlEncode
	 * @param 	string $url  	L'indirizzo internet da elaborare - non c'è bisogno di ripetere l'indirizzo di base
	 * @return  void
	*/
	static function cleanUrl($url) {
		$url = str_replace("\\", "/", $url);
		$url = explode(":/", $url);
		$tempUrl = array_pop($url);
		while ($tempUrl != str_replace('//', '/', $tempUrl)) {
			$tempUrl = str_replace('//', '/', $tempUrl);
		}
		if (count($url) > 0) {
			$url = $url[0]. ":/". $tempUrl ;
		} else {
			$url = $tempUrl ;
		}
		$url = str_replace("&amp;","&", $url);
		return $url;
	}
	/*
	 * Aggiunge una parte della query
	 * @params string|array $query
	 * @return  void
	*/
	public function addQuery($query) {
		if (is_string($query)) {
			$query = str_replace("&amp;","&", $query);
			$arrayQueries = explode("&", $query);
			foreach ($arrayQueries as $singleQuery) {
				$temp = explode("=", $singleQuery);
				if (@$temp[0] != "" && @$temp[1] != "") {
					$this->url['queries'][trim($temp[0])] = urlencode(urldecode(trim($temp[1])));
				}
			}
		} else if (is_array($query)) {
			$arrayQueries  = $query;
			foreach ($arrayQueries as $key=>$value) {
				if (is_string($key) ) {
					if (is_array($value)) {
						foreach ($value as $kkk=>$vvv) {
							if (is_array($vvv)) {
								foreach ($vvv as $k4=>$v4) {
									if (is_string($v4)) {
										$this->url['queries'][trim($key)][trim($kkk)][$k4] = urldecode(trim($v4));
									}
								}
							} else {
								$this->url['queries'][trim($key)][trim($kkk)] = urldecode(trim($vvv));
							}
						}
					} else if(is_string($value)) {
							$this->url['queries'][trim($key)] = urldecode(trim($value));
					}
				}
			}
		}
	}
	/*
	 * Aggiunge una parte della path
	 * @params string $path
	 * @return  void
	*/
	public function addPath($path) {
		if (is_string($path)) {
			$path = explode("/", GPURLParser::cleanUrl($path));
		}
		$path  = array_filter($path ,  function ($item) {
			if ($item != "." && $item != ".." && $item != "/" && trim($item) != "") {
	 			return true;
			} else {
				return false;
			}
		});

		foreach ($path as $keyP=>$p) {
			if (strpos($p, ".") !== false && $keyP == count($path)-1) {
				$this->url['basename'] = urlencode($p);
			} elseif ((is_string($p) || is_numeric($p)) && trim($p) != "") {
				$this->url['dirname'][] = urlencode($p);
			}
		}

	}
	/*
	 * Restituisce solo la parte di path aggiunta + il dirname
	 * @return  array
	*/
	public function getPath($basename = false) {
		$registry = GPRegistry::getInstance();
		$base = str_replace(array("http://","https://",$_SERVER["SERVER_NAME"]), "", $_SERVER["SERVER_NAME"] .$registry->get('url.base', "/"));
		$baseArray = explode("/", $base);
		$baseArray = array_filter($baseArray);
		$path = $this->url['dirname'];
		$path = array_splice($path, count($baseArray));
		if ($basename && @$this->url['basename'] != '') {
			$path[] = $this->url['basename'];
		}
		return $path;
	}
	/*
	 * Converte il path in una stringa
	 * @return  string
	*/
	public function pathToString() {
		$this->url['dirname'] = array_filter($this->url['dirname'],  function ($item) {
			if ($item != "." && $item != ".." && $item != "/" && trim($item) != "") {
	 			return true;
			} else {
				return false;
			}
		});
		foreach ($this->url['dirname'] as $key=>$val) {
			$this->url['dirname'][$key] = urlencode($val);
		}
		return "/".implode("/", $this->url['dirname'] );
	}
	/*
	 * Converte il la query in una stringa
	 * @params	boolean $basename  dice se aggiungere o no il basename
	 * @return  string
	*/
	public function queryToString($basename = false, $adding = false) {
		$this->tempUrl = $this->url;
		if ($adding) {
			$this->addQuery($adding);
		}
		$url = "";
		if (@count($this->url['queries']) > 0) {
			$query = array();
			foreach ($this->url['queries'] as $key=>$value) {
				if (trim($key) != "") {
					if (is_array($value)) {
						foreach ($value as $kkk=>$vvv) {
							if (is_array($vvv)) {
								foreach ($vvv as $k4=>$v4) {
									if (is_string($v4)) {
										$query[] = urlencode(trim($key))."[".$kkk."][".$k4."]"."=".urldecode(trim($v4));
									}
								}
							} else {
								$query[] = urlencode(trim($key))."[".$kkk."]"."=".urlencode($vvv);
							}
						}
					} else if (is_string($value)){
						$query[] = urlencode(trim($key))."=".urlencode($value);
					}
				}
			}
			if (count($query) > 0) {
				$url .= "?".implode("&",$query);
			}

		}
		$this->url = $this->tempUrl;
		if ($basename && $this->url['basename'] != "") {
			return $this->url['basename'].urldecode($url);
		} else {
			return urldecode($url);
		}
	}
	/*
	 * Converte tutta la query in una stringa
	 * @params string $addingUrl  Una parte di stringa da aggiungere temporaneamente alla query
	 * @return  string
	*/
	public function toString($addingUrl = "") {
		$this->tempUrl = $this->url;
		$this->add($addingUrl);
		if (@$this->url['scheme']) {
			$scheme = $this->url['scheme']."://";
		} else {
			$scheme = "";
		}
		// TODO $this->base.$this->pathToString(); e il path è solo quello dopo la base!!!!!!!!!!!!
		if (@count($this->url['dirname']) > 0 && @$this->url['basename'] != "") {
			$url = $scheme. @$this->url['host'].$this->pathToString()."/".$this->url['basename'];
		} elseif (@count($this->url['dirname']) > 0) {
			$url = $scheme. @$this->url['host'].$this->pathToString();
		}elseif (@$this->url['basename'] != "") {
			if (substr(@$this->url['host'],-1) != "/" && substr($this->url['basename'],0,1) != "/") {
				$url = $scheme. @$this->url['host']."/".$this->url['basename'];
			} else {
				$url = $scheme. @$this->url['host'].$this->url['basename'];
			}
		} else {
			$url = $scheme. @$this->url['host'];
		}
		$url .= $this->queryToString();
		$this->url = $this->tempUrl;
		if ($this->extension != "") {
			$ext = pathinfo ( $url, PATHINFO_EXTENSION );
			if ($ext == "") {
				$url .=".".$this->extension;
			}
		}
		return trim(str_replace("///","//", $url));
	}
	/*
	 * Aggiunge una parte di query
	 * @params string $addingUrl  Una parte di stringa da aggiungere alla query
	 * @return  void
	*/
	public function add($addingUrl = "") {
		if ($addingUrl != "") {
			$addingUrl = $this->cleanUrl($addingUrl);
			$splitUrl = explode("?", $addingUrl);
			if (count($splitUrl) == 2) {
				$this->addQuery($splitUrl[1]);
			}
			$this->addPath($splitUrl[0]);
		}
	}

}
