<?php
/**
 * Classe per la gestione dei Log
 */
class GpLog
{
    var $logs = array();
    var $uniqid;
    var $pointerHtml = "";
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
            self::$instance->uniqid = uniqid();
        }
        return self::$instance;
    }
    /**
     * Aggiunge un log ad un gruppo di log
    * @param String $group  Il nome del file in cui scrivere e il gruppo di dati da scrivere
     * @param String $error Il tipo di messaggio è un testo libero es:  (LoadModule|Require|Info|Notice|Warning|Error|FatalError)
     * @param String $msg [Opzionale] Il messaggio da scrivere
     * @param Array $params [Opzionale] Eventuali parametri da stampare in [key "value"]
     * @param Boolean $path [Opzionale] se inserire dopo il messaggio i file che hanno portato chiamare quel messggio
     */
    public function set($group, $msgType, $msg = "", $params = "", $path = true) {
        $in = false;
        if (is_array($path)) {
            $in = $path;
        }
        if ($path === true) {
            $debug = (debug_backtrace());
            if (count ($debug) > 0) {
                array_shift($debug);
            }
            if (count ($debug) > 0) {
                $in = array();
                foreach ($debug as $d) {
                    $in[] = $d['file'].":".$d['line'];
                }
            }
        } 
        if (!array_key_exists($group, $this->logs)) {
            $this->logs[$group] = array();
        }
        $this->logs[$group][] = array('msgType'=>$msgType, 'msg'=>$msg, 'params' =>$params, 'time'=>date('YmdHis'), "in"=> $in, "pointerHtml" => $this->pointerHtml);
    }
    /**
     * 
     */
    public function get($group) {
        if (array_key_exists($group, $this->logs)) {
            return $this->logs[$group];
        } else {
            return array();
        }
    }
    /**
     * Scrivo su file tutti i log fino ad ora impostati per un determinato gruppo.
     * Tutti i parametri sono opzionali. Se viene inserito $error allora imposta prima una stringa da salvare, poi la salva, altrimenti stampa tutti i messaggi in coda precedentemente impostati in set.
     * @param String $group  [Opzionale] default System. Il nome del file in cui scrivere e il gruppo di dati da scrivere
     * @param String $error [Opzionale] Il tipo di messaggio è un testo libero es:  (LoadModule|Require|Info|Notice|Warning|Error|FatalError)
     * @param String $msg [Opzionale] Il messaggio da scrivere
     * @param Array $params [Opzionale] Eventuali parametri da stampare in [key "value"]
     * @param Mixed $path [Opzionale] Se inserire dopo il messaggio i file che hanno portato chiamare quel messggio. Boolean o l'array con il path
     */
    public function write($group = "system", $msgType = "", $msg = "", $params = "", $path = true) {
        if ($msgType != "") {
            $this->set($group, $msgType, $msg, $params, $path);
        }
        $logDir = Gp::load()->getPath('assets')."/logs";
        if (!is_dir($logDir)) {
            mkdir($logDir);
        }
        $logFile = $logDir."/".strtolower($group).".log";
        $textToAppend = "";
        if (array_key_exists($group, $this->logs)) {
            foreach ( $this->logs[$group] as $logString) {
                $typeMsg =  ($logString['msgType']== "") ? "-": $logString['msgType'];
                $msg =  ($logString['msg']== "") ? "-": $logString['msg'];
                $add = array();
                if (array_key_exists('params', $logString) && is_array($logString['params'])) {
                    $add = json_encode($logString['params']);
                    $add = str_replace(array( "\n\r", "\r\n",  "\n", "\r"), " ", $add);
                }  else {
                    $add = " -";
                }
                if (array_key_exists('in', $logString) && is_array($logString['in']) && count ($logString['in']) > 0) {
                    $in = " ". $this->cleanStr(implode(" # ",$logString['in']));
                } else {
                    $in = " -";
                }
                $textToAppend .= $logString['time']. " ".$this->uniqid . " " . $this->cleanStr($this->get_client_ip()) . " " . $this->cleanStr($typeMsg) . " " . $this->cleanStr($msg) . $in .  $add . "\n";
            }
            unset($this->logs[$group]);
        }
        if ($textToAppend != "") {
            // rotazione dei file
            if (is_file($logFile)) {
                $config = array('size'=>'5', 'max_files'=>5);
                $bytes = filesize ($logFile);
                // converto in MB
                $kb = $bytes / 1024;
               // print "config: ".Gp::data()->get('config.log.max_files', '1');
                if ($kb > Gp::data()->get('config.log.size', '1024')) {
                    $newName = $logDir."/".strtolower($group)."_".date('YmdHis').".log";
                    
                    if (!is_file ($newName)) {
                        rename($logFile , $newName);
                        // Conto il numero dei log permessi
                        $cdir = scandir($logDir); 
                        $arrayLogs = array();
                        foreach ($cdir as $key => $value) { 
                            if (!in_array($value,array(".","..")) && is_file($logDir."/".$value))  { 
                                $ext = explode(".", $value);
                                if (count($ext) > 0) array_pop($ext);
                                $name = explode("_", implode(".", $ext));
                                $logTime = array_pop($name);
                                $logName = implode("_", $name);
                                //print "<p>".$value. " ".$logTime." ".$logName."</p>";
                                if (strtolower($group) == $logName) {
                                $arrayLogs[$logTime] = $value;
                                }
                            }
                        }
                        ksort($arrayLogs);
                        while (count ($arrayLogs) >= Gp::data()->get('config.log.max_files', '5')) {
                            $toRemove = array_shift($arrayLogs);
                            //print "<p>REMOVE ".$toRemove."</p>";
                            unlink($logDir."/".$toRemove);
                        }
                    }
                }
            }
            file_put_contents($logFile, $textToAppend, FILE_APPEND);
        }
        
    }
    /**
     * LOAD Carica i dati da un file e li ridivide con un'espressione regolare
     * @param String $filename  il nome del file
     * @param Int $filterTimeStart  la data di inizio dell'intervallo di log che si vuole prendere formata da YmdHis
     * @param Int $filterTimeEnd la data di fine dell'intervallo di log che si vuole prendere formata da YmdHis
     */
    function load($fileName, $filterTimeStart = 0, $filterTimeEnd = 99999999999999) {
        $logDir = Gp::load()->getPath('assets')."/logs";
        $logFile = $logDir."/".$fileName.".log";
        
        $re = '/([0-9]{14})\s([a-z0-9]*?)\s((?!\").*?|\"(?!\\\\\").*?\")\s((?!\").*?|\"(?!\\\\\").*?\")\s((?!\").*?|\"(?!\\\\\").*?\")\s((?!\").*?|\"(?!\\\\\").*?\")\s((?!\").\s*|\"\s(?!\\\\\").*\")/';
       // $str = file_get_contents($logFile);
        $handle = fopen($logFile, "r");
        if ($handle) {
            $logs = array();
            while (($line = fgets($handle)) !== false) {
                if (substr($line,0,14) >=  $filterTimeStart && substr($line,0,14) <=  $filterTimeEnd){
                    preg_match_all($re, $line, $matches, PREG_SET_ORDER, 0);
                    $mc = $matches[0];
                    $log = array();
                    $log['time'] = $mc[1];
                    $log['uniqId'] = $mc[2];
                    $log['ip'] =  $mc[3];
                    $log['type'] = $this->logStr($mc[4]);
                    $log['msg'] = $this->logStr($mc[5]);
                    $log['path'] =  explode(" # ", $mc[6]);
                    if ($mc[6] != "-") {
                        $log['params'] = json_decode($mc[7]);
                        if ($log['params'] === null && json_last_error() !== JSON_ERROR_NONE) {
                            $log['params'] = array();
                        }
                    } else {
                        $log['params'] = array();
                    }
                    $add = true;
                    if ($add) {
                        $logs[] = $log;
                    }
                }
            }
            fclose($handle);
            return $logs;
        } else {
            return false;
        } 
        
    }
    /**
     * Pulisce la stringa prima di salvarla nel log
     * @param String $string
     * @return String
     */
    function cleanStr($string) {
        if (is_array($string) || is_object($string)) {
            return json_encode($string);
        } 
        $string = str_replace(array("\\", "\n","\r",'"', "  "), array("\\\\", "" ,"" ,'\"', " "), $string);
         $string = trim($string);
        if (strpos($string, " ") !== false) {
            $string = '"'.$string.'"';
        }
        return $string;
    }
    /** 
     * Pulisce la stringa di un log che era stata modificata con cleanStr
    * @param String $string
     * @return String
     */
    function logStr($string) {
        $string = trim($string);
        if (substr($string,0,1) == '"') {
            $string = substr($string,1);
        }
        if (substr($string,-1,1) == '"') {
            $string = substr($string,0, -1);
        }
        return stripslashes($string);
    }

    /** 
     * Trova l'ip
     * @return String
     */
    function get_client_ip() {
        $ipaddress = '-';
        if (getenv('HTTP_CLIENT_IP')) $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))   $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))   $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))  $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))  $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))  $ipaddress = getenv('REMOTE_ADDR');
        else $ipaddress = 'UNKNOWN';

        if ($ipaddress == "::1") $ipaddress = "127.0.0.1";
        return $ipaddress;
    }
    /**
     * Imposta un nome di una variabile per collegare l'html ai messaggi
     */
    function setPointerHTML() {
        $this->pointerHtml = uniqid();
    }
    /**
     * Imposta un nome di una variabile per collegare l'html ai messaggi
     */
    function getPointerHTML() {
        return $this->pointerHtml;
    }
    /**
     * Stampa in un tag html il data log
     */
    function getDataLog() {
        if (Gp::data()->get('config.log.printDataLog', true) && $this->pointerHtml != "") {
            return ' data-log="'.$this->pointerHtml.'"';
        }
    }


}

register_shutdown_function( "fatal_handler" );
function fatal_handler() {
    $errfile = "unknown";
    $errstr  = "shutdown";
    $errno   = E_CORE_ERROR;
    $errline = 0;
    
    $error = error_get_last();
    $params = array();
    if( $error == NULL) {
        $params = array('file'=>"unknown", 'line'=>'0');
        $msg = "shutdown";
    } else {
        $msg = $error["message"];
    }
    $errno   = $error["type"];
    $errfile = $error["file"];
    $errline = $error["line"];
    $errstr  = $error["message"];
    
    if ($msg != "shutdown" && $errline != 0) {
        if (Gp::data()->get('config.log.write_error', false)) {
            Gp::log()->write('error', 'FATALERROR', $msg, false, array($errfile.":".$errline));
        }
        Gp::action()->invoke("logOnFatalHandler", $error );
    }
}
