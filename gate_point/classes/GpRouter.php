<?php
/**
 * Classe gestione Router
 */
class GpRouter
{
    var $dirRoot = "/"; // la directory del sito
    var $relativePath = "";  // il path in più per i siti che partono da una sottocartella o con htaccess
    var $scheme = "http";
    var $site = ""; // l'home del sito
    var $fnParse = null;
    var $htaccess = true; // dice se usare l'htaccess oppure no
    var $fnBuild = null;
    static $instance;
    /**
	 * Ritorna il singleton della classe
	 * @return  	singleton
	**/
	public static function getInstance() {
        if(self::$instance == null)
        {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

     function __construct($dirRoot = "", $scheme = "", $site= "") {
         $this->setConfig($dirRoot, $scheme, $site);
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
            $this->site = $_SERVER["HTTP_HOST"].$this->relativePath;
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
     * @param Boolean|String $getUri Boolean: Se avere le query oppure no nell'url corrente. String: aggiunge parametri all'url corrente
     * @return String
     */
    function getCurrentLink($getUri = true) {
        if (is_string($getUri)) {
            $url = $this->parseUrl();
            $url2 = $this->parseUrl($getUri, false);
            $newUrlQuery = array_filter(array_merge($url['query'], $url2['query']));
            return $this->getLink($newUrlQuery);
            
        } else if ($getUri === true) {
            return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
        }
        if ($getUri === false) {
            return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://".$_SERVER["HTTP_HOST"].strtok($_SERVER["REQUEST_URI"],'?'); 
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
     * Trasforma un link nel percorso della directory
     */
    function linkToDir($link = "") {
        $realPath = $this->parseUrl($link, false);
        $path = "";
        if (array_key_exists("path", $realPath) && count ($realPath['path'])) {
           $path = implode ("/", $realPath['path']);
        }
        return $this->getDir().$path;
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
     * @param String|Array $query il link ad esempio esempio/index.php?var=1 oppure ('var'=>1)
     * @param Boolean  $useFunction dice se usare la funzione personalizzata oppure no
     * @return String 
     */
    function getLink($query = "", $useFunction = true) {
        if (is_array($query)) {
            $tempQuery = array();
            foreach ($query as $k=>$v) {
                if (is_array($v)) {
                    $tempQuery = $this->_recursiveImplodeQuery($v, $k);
                } else {
                    $tempQuery[] = $k."=".$v;
                }
            }
            $query = "/?".implode("&", $tempQuery);

        } else if (is_string($query)) {
            $query = str_replace(array($this->scheme . "://", $this->site), '', $query);
            if (substr($query,0,1) != "/") {
                $query = "/".$query;
            }
        }
        if ($this->fnBuild != null && $useFunction) {
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
     * @param String  $link 
     * @param Boolean  $useFunction dice se usare la funzione personalizzata oppure no
     * @return Array ('scheme':string,'host':string, 'path':string,'filename':string,'pathQuery':array,'query':array)
     */
    function parseUrl($link = "", $useFunction = true) {
        if ($link == "") {
            $link = $this->getCurrentLink();
        }
        $ris = parse_url($link);
        if (array_key_exists('path', $ris)) {
            //$ris['filename'] = basename($ris['path']);
            $risPath = $ris['path'];
            $ris['path'] = array_values (array_filter(explode("/",  str_replace($this->relativePath, '', $risPath))));
        }
        
        if (array_key_exists('query', $ris)) {
            $ris['query'] = str_replace("&amp;", "&", $ris['query']);
            $temp = explode("&", $ris['query']);
            $t3 = array();
            foreach ($temp as $val) {
                $temp2 = explode("=", $val);
                $t3[$temp2[0]] = $temp2[1]; 
            }
            $ris['query'] = $t3;
        }
        if ($this->fnParse != null && $useFunction) {
            return call_user_func_array($this->fnParse, array($ris, $this));
        } 
        return $ris;
    }
    /**
     * PathToQuery converte una path in query secondo lo schema passato:  $routerClass->pathToQuery($parseUrl, "page", "path");
     * Lo si usa dentro la funzione personalizzata del rooter per gestire i dati
     * @param Array  $parseUrl 
     * @param args [query1, query2, query3, ...]
     * @return Array
     */
    function pathToQuery($parseUrl) {
        $args = func_get_args();
        array_shift($args);
        if (count($args) > 0) {
            if (!array_key_exists('query',$parseUrl)) {
                $parseUrl['query'] = array();
            }
            if (!array_key_exists('path',$parseUrl)) {
                $parseUrl['path'] = array();
            }
            foreach ($args as $arg) {
                if (count($parseUrl['path']) > 0) {
                    if (!array_key_exists($arg, $parseUrl['query'])) {
                        $parseUrl['query'][$arg] = array_shift($parseUrl['path']);
                    } else {
                        array_shift($parseUrl['path']);
                    }
                }
            }
        }
        if (count ($parseUrl['path']) == 0) {
            unset($parseUrl['path']);
        }
        return ($parseUrl);
    }
    /**
     * queryToPath converte una query in path secondo lo schema passato:  $routerClass->pathToQuery($parseUrl, "page", "path");
     * Lo si usa dentro la funzione personalizzata del rooter per gestire i dati
     * @param Array  $parseUrl 
     * @param args [query1, query2, query3, ...]
     * @return String il link creato dalla query
     */
    function queryToPath($parseUrl) {
        $args = func_get_args();
        array_shift($args);
        $newPath = array();
        if (count($parseUrl['path']) > 1) {
             return "/".implode("/", $parseUrl['path']) . $this->implodeQuery($parseUrl['query']);
        }
        if (count($args) > 0) {
            if (!array_key_exists('query',$parseUrl)) {
                $parseUrl['query'] = array();
            }
            if (!array_key_exists('path',$parseUrl)) {
                $parseUrl['path'] = array();
            }
            foreach ($args as $arg) {
                if (array_key_exists($arg, $parseUrl['query'])) {
                    $newPath[] = $parseUrl['query'][$arg];
                    unset($parseUrl['query'][$arg]);
                } else {
                    return "/".implode("/", $newPath).$this->implodeQuery($parseUrl['query']);
                }
            }
        }
       return "/".implode("/", $newPath).$this->implodeQuery($parseUrl['query']);
    }
    /**
     * Verifica se la pagina è quella attiva
     * @param String $link Il link da verificare
     * @param String $currentLink opzionale Il link della pagina 
     * @param Array $whichQueryCheck opzionale l'array di query da verificare perché il link sia lo stesso. Questo per evitare parametri aggiuntivi che comunque non modificherebbero la pagina
     * @return Boolean
     */
    function isActive($link, $currentLink = "", $whichQueryCheck = false) {
        $a = $this->parseUrl($currentLink);
        $b = $this->parseUrl($link);

        if (array_key_exists('path', $a) && array_key_exists('path', $b)) {
            if ($a['path'] != $b['path']) return false;
        }
        if (!array_key_exists('query', $a) || count ($a['query']) == 0) {
            return  ((array_key_exists('query', $b) && count ($b['query']) == 0) || !array_key_exists('query', $b));
        }
         if (!array_key_exists('query', $b) || count ($b['query']) == 0) {
            return  ((array_key_exists('query', $a) && count ($a['query']) == 0) || !array_key_exists('query', $a));
        }
        if (array_key_exists('query', $a) && array_key_exists('query', $b)) {
            if ($whichQueryCheck != false) {
                foreach ($whichQueryCheck as $wqc) {
                    if (array_key_exists($wqc, $a['query']) && array_key_exists($wqc, $b['query'])) 
                    if ($a['query'][$wqc] != $b['query'][$wqc]) return false;
                }
            } else {
                if ($a['query'] != $b['query']) return false;
            }
           
        }
        return true;
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
                if (is_array($value)) {
                    $list = $this->_recursiveImplodeQuery($value, $key);
                } else {
                    $list[] = $key."=".$value;
                }
            }
            return "?".implode("&", $list);
        }
        return "";
    }
    /**
     * Server per gestire la costruzione di query con array annidati
     * @param Array  $query la query da trasformare in stringa
     * @param String $base la chiave da cui è partito l'array es {"a":{"b":"1","c":"2"}} la chiave è a e ritornerà ["a[b]=1", "a[c]=2"]
     * @return Array
     */
    private function _recursiveImplodeQuery($query, $base="") {
        $tempQuery = array();
        foreach ($query as $k=>$v) {
            $basek =  $base."[".$k."]";
            if (is_array($v)) {    
                $tempQuery = array_merge($tempQuery, $this->_recursiveImplodeQuery($v, $basek));
            } else {
                $tempQuery[] = $basek."=".$v;
            }
        }
        return $tempQuery;
    }

}