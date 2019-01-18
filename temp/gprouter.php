<?php
/**
 * GP Router
 * Gestisce l'url rewrite del sito
 */
defined('_GPEXEC') or die;
/**
 * GPRouter
 * Gestisce l'url rewrite del sito
 * @package     	app
 * @subpackage		platform
 * @example 		../test-app/index.php
*/
class GPRouter
{
	/*
	 * @var 		GPRoute  	L'istanza della classe per il singleton
	*/
	private static $instance 	= null;

	/**
	 * @var 		array  	le regole del parsing
	*/
	private static $roules 	= null;
	/**
	 * @var 		array  	Le query riscritte dopo l'url
	*/
	public $queries 	= array();
	/**
	 * @var 		boolean  	Se l'url della pagina è riscritto o no
	*/
	public $urlRewrite 	= false;
	/**
	 * @var 		string	  extension l'estensione a fine path es: .html
	*/
	public $extension = "";
	/**
	 * @var 		array	  l'array delle estensioni della lingua accettate
	*/
	//public $languages = array();
  //public $defaultLang = "";
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
	 * Aggiunge una nuova regola per l'url rewrite
	 * le regole sono formate da due parti la prima è il nome della pagina, la seconda è la path che si deve creare
	 * esempio array('article', '/@option:article/id:|[0-9]') in questo caso saranno contrassegnate come article tutte le pagine
	 * formate da /article/[int] dove [int] sarà un numero convalidato attraverlo l'espressione regolare [0-9].
	 * @params array $rules 	Una o più regole. Ogni regola è deve essere composta da un array di 2 dimensioni
	 * @return void
	 */
	public function append($roules) {
		foreach ($roules as $mapping) {
			if (is_array($mapping)) {
				$this->_appendSingle($mapping);
			} else {
				$this->_appendSingle($roules);
				break;
			}
		}
	}
	public function clearRules() {
		$this->rules = array();
	}
	/**
	 * Analizza un indirizzo e ne ritorna la pagina.
	 * $urlPath
	 * @return il numero di regole che soddisfano l'url
	 */
	public function route($url, $queries = false) {
		$urlPath = $url;
		$this->urlRewrite = false;
		$this->queries = array();
		if (is_string($urlPath)) {
			$urlMapper = new GPUrlParser($urlPath);
			$urlPath = $urlMapper->getPath(true);
			$queries = $urlMapper->url['queries'];
		}

		foreach ($this->rules as $key=>$rules) {
			if (count($rules['segments']) >= count($urlPath)) {
				reset($urlPath);
				$accept = 0;
				$takeSegment = true;
				foreach ($rules['segments'] as $rule) {
					$currentUrlPath = current($urlPath);
					if ($rules['segments'][count($rules['segments'])-1] == $rule && $this->extension != "") {
						$currentUrlPath = str_replace($this->extension, "", $currentUrlPath);
					}
					if ($this->compareSegments($rule, $currentUrlPath, $queries, $rules['variables'] != false)) {

						$accept++;
					} else {
						$takeSegment = false;
						break;
					}
					next($urlPath);
				}
				reset($urlPath);
				if ($accept == count($rules['segments']) && $takeSegment) {
					$this->urlRewrite = true;
					// aggiungo al registry i parametri
					$registry = GPRegistry::getInstance();
					if ($rules['variables'] != false) {
						foreach ($rules['variables'] as $rule) {
							if (substr($rule['roule'],0,1) != "|" && substr($rule['roule'],0,1) != "*") {
								$key = trim(str_replace("@","",$rule['path']));
								$registry->set($rule['roule'], 'request.'.$key);
								$this->queries[$key] = $rule['roule'];
							}
						}
					}
					if ($rules['segments'] != false) {
						foreach ($rules['segments'] as $rule) {
							$key = trim(str_replace("@","",$rule['path']));
							$value = current($urlPath);
							if (substr($rule['path'],0,1) == "@" &&  @$queries[$key] != '') {
								$value = $queries[$key];
							}
							// doppio urldecode per gli / (%252F) sugli url
							$value = urldecode(urldecode($value));
							if ($key != 'NOKEY') {
								$this->queries[$key] = $value;
								$registry->set($value, 'request.'.$key);
							}
							next($urlPath);
						}
					}
					return $rules['path'];
				}
			}
		}
		// prima di dire che non esiste un path cerco di riscriverlo
		// per vedere se i parametri passati sono sufficienti per generare un link associabile ad un rooter

		// sull'url ci devo aggiungere tutti i get e post....
		$tempUrl = new GPUrlParser($url);
		$urlErrorMsg = 	$tempUrl->toString();
		$tempUrl->addQuery(GPApp::get("request"));
		$url = $this->build($tempUrl->toString(), true, false);
		if ($this->lastBuildUrl > 0) {
			return $this->lastBuildUrl;
		}
		return false;
	}
	/*
	 * fa l'url rewrite di un link
	 * @param   link		string		Il link da riscrivere es: ?key=value&key2=value2
	 * @param   urlRewrite	boolean		se deve riscrivere il link o no
	 * @return string
	 */
	public function build($link = '', $urlRewrite = true, $debug = true) {

		$this->lastBuildUrl = 0;
		$urlMapper = new GPUrlParser($link);

		// vedo se il controller deve aggiungere parametri // NON DEVE STARE DENTRO IL FOREACH !!!!!
		$originalQueries  =  @$urlMapper->url['queries'];
		if (!is_array($originalQueries)) {
			$originalQueries = array();
		}
		// se cerco una pagina speciale es: GPApp::buildUrl('?special=home')
		if (array_key_exists('special', $originalQueries) && !array_key_exists('app', $originalQueries)) {
			$rooter = GPConfig::getRooter();
			foreach ($rooter as $r) {
				if ($r['special'] == $originalQueries['special']) {
					$newUrlMapper = new GPUrlParser($r['url']);
					unset($originalQueries['special']);
					$newUrlMapper->addQuery($originalQueries);
					return $this->build($newUrlMapper->toString());
				}
			}
 		}
		 $langs = GPConfig::get('lang');
		 if (is_array(@$langs) && count(@$langs) > 1)  {
			 $langs = true;
		 } else {
			 $langs = false;
		 }
		// vedo se il controller deve aggiungere parametri // NON DEVE STARE DENTRO IL FOREACH !!!!!
		if (array_key_exists('app', $originalQueries)) {
			if (is_file(GPATH_SITE.DS.'applications'.DS.$originalQueries['app'].DS.'controller.php')) {
				require_once(GPATH_SITE.DS.'applications'.DS.$originalQueries['app'].DS.'controller.php');
				$className = 'Controller'.$originalQueries['app'];
				$functionName = "buildUrl";
				$tmpObject = new $className;
				if (method_exists($tmpObject, $functionName)) {
					$originalQueries =  $tmpObject->$functionName($originalQueries);
				}
			}
		}

		foreach ($this->rules as $key=>$rules) {
			$accept = 0;
			$takeSegment = true;
			$newUrl = array();
			$queries =  $originalQueries;

			if ($rules['variables'] != false) {
				$ruleSegments = $rules['variables'];
				$alias = true;
			} else {
				$ruleSegments = $rules['segments'];
				$alias = false;
			}
			// Se viene passata la query lang_name impongo la ricerca solo ai rooter con quella lingua
			if (array_key_exists("lang_name", $queries) && $langs) {
				$rooter = GPConfig::getRooterById($rules['page']);
				if ($rooter['lang_name'] != $queries['lang_name']) {
					continue;
				}

			}
			foreach ($ruleSegments as $rule) {
				$urlPath =  @$queries[str_replace("@","",$rule['path'])];

				if ($urlPath != false) {
					if ($this->compareSegments($rule, $urlPath, $queries)) {
						//print "OK";
						$accept++;
						if (substr($rule['path'],0,1) == "@" && @$queries[str_replace("@","",$rule['path'])] != '') {
							$newUrl[] = $queries[str_replace("@","",$rule['path'])];
						} else {
							$newUrl[]  = $urlPath;
						}
					} else {
						//print "NO";
						$takeSegment = false;
						break;
					}
					unset($queries[str_replace("@","",$rule['path'])]);
				}
			}

			if ($accept == count($ruleSegments) && $takeSegment) {
				$returnURL =  new GPUrlParser('base');
				if (array_key_exists("lang_name", $queries)) {
					unset($queries["lang_name"]);
				}
				if ($alias) {
					$addPath = array();
					foreach ($rules['segments'] as $rs) {
						if ($rs['path'] == "@NOKEY" || substr($rs['path'],0,1) != "@") {
						$addPath[] = $rs['roule'];
						} else if (substr($rs['path'],0,1) == "@") {
						if (@$originalQueries [str_replace("@","",$rs['path'])]) {
						$addPath[]  = $originalQueries [str_replace("@","",$rs['path'])];
						unset($queries[str_replace("@","",$rs['path'])]);
						}
          }
				}
				$returnURL->addPath($addPath);
			} else {
				$returnURL->addPath($newUrl);
			}
				// aggiungo eventuali altre query
				$returnURL->addQuery($queries);
				$this->lastBuildUrl = $rules['page'];
				if ($urlRewrite == false) {
					return $urlMapper->toString();
				} else {
					$link = $returnURL->toString();
					if (GPApp::get("url.sitepath")."index" == $link) {
						$link = GPApp::get("url.sitepath");
					}
					return $link;
				}
			}
		}
		return $urlMapper->toString();
	}
	/*
	* Regole dei segmenti
	* @return boolean
	*/
	private function compareSegments($internal, $external, $queries = array(), $alias = false) {
	   // print ("<p>INTERNAL ROULE: ".$internal['roule']." EXTERNAL ".$external."</p>");
		// @ accetta sempre i parametri GET per primi, # accetta solo i parametri del path
		// # crea problemi se hai basename != '' perché lo interpreta come primo path
		if (substr($internal['path'],0,1) == "@") {
			if (@strlen($internal['roule']) == 0) {
				return true;
			}
			if (substr($internal['path'],0,1) == "@" && @$queries[str_replace("@","",$internal['path'])] != '') {
				$external = $queries[str_replace("@","",$internal['path'])];
			}
			if($external == false) {
				if ($internal['roule'] == "NULL") {
					return true;
				} else {
					if (substr($internal['path'],0,1) == "@" && $internal['path'] != "@NOKEY") {
						// parametri opzionali settati nell'alias (ovvero quando c'è la @ nell'alias
						return $alias;
					} else {
						return false;
					}
				}
			}
			switch (substr($internal['roule'],0,1))
			{
				// accetta tutto tranne il null
				case '*':
					if (is_array($external)) return true;
					return ($external != '') ;
					break;
				// regular expression
				case '|':
					$regExp = substr($internal['roule'],1,-1);
					//print ("<p>REGULAR EXPRESSION : ".$regExp." external: ".$external."</p>" );
					if (is_array($external)) {
						foreach ($external as $ex) {
							$ret = (preg_match("/".$regExp."/i", $ex)) ;
							if ($ret == true) {
								return true;
							}
						}
						return false;
					} else {
						if (preg_match("/".$regExp."/i", $external)) {
							return true;
						} else {
							return false;
						}
					}
					break;
				// constant
				default:
					if (is_array($external)) {
						foreach ($external as $ex) {
							if ($ex == $internal['roule']) return true;
						}
						return false;
					}
					return ($external == $internal['roule']) ;
					break;
			}
		} else {
			if ((string)$external == (string)$internal['path']) {
				return true;
			}
		}
		return false;
	}
	/*
	 * Aggiunge una singola regola a $this->rules
	 * @params array $mapping
	 * @return void
	 */
	private function _appendSingle($mapping) {
		$page 			= $mapping[0];
		$map  			= $mapping[1];
		$path 			= $page;
		if (array_key_exists(2, $mapping)) {
			$variable = explode("/", $mapping[2]);
			$variable			= array_filter($variable);
			$variables = array();
			foreach ($variable as $var) {
				$temp		= explode(":", $var);
				if (count($temp) == 1) {
					$temp[0] = '@NOKEY';
					$temp[1] = $var;
				}
				$variables[] = array('path'=>$temp[0], 'roule'=>@$temp[1]);
			}
			$path = trim($page).trim($mapping[1]);
		} else {
			$variables = false;
		}

		$maps			= explode("/", $map);
		$maps			= array_filter($maps);
		$segments		= array();
		foreach ($maps as $map) {
			if ($map != "") {
				$temp		= explode(":", $map);
				if (count($temp) == 1 ) {
					$temp[0] = '@NOKEY';
					$temp[1] = $map;
				} else if (count($temp) == 2 && $temp[0] == '' ) {
					$map = $temp[1];
					$temp[0] = '@NOKEY';
					$temp[1] = $map;
				}
				$segments[] = array('path'=>$temp[0], 'roule'=>@$temp[1]);
			}
		}
		$this->rules[] = array("page"=>$page, "segments"=>$segments, "variables"=>$variables, "path"=>$path);
	}
}
?>
