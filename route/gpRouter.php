<?php
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
     * @param String  $link 
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
