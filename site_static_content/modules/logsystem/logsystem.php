<?php 
class module_logsystem
{
    /**
     * Carica un determinato tipo di log e lo visualizza in forma di tabella
      */
    function getCurrentPage($logType) {
         if ($logType == null) {
           $logType == 'system';
        }
        $items = new GpRegistry();
        $items->set('logs', Gp::log()->get($logType));
        ob_start();
        require(dirname(__FILE__)."/view_log_table.php"); 
        return ob_get_clean();
        
    }
    /** 
     * Estrae il log system di una determinata pagina
     * Estrae tutto il log per una specifica pagina
     */
    function getPage($pageName) {
        if ($pageName == null) {
            return array('error'=>'DATA','data_example'=>'&pageName=home');
        }
        ob_start();
        Gp::load()->require('pages', $pageName.".php");
        ob_get_clean();
        $items = new GpRegistry();
        $items->set('logs', Gp::log()->get('system'));
        ob_start();
        require(dirname(__FILE__)."/view_log_table.php"); 
        return ob_get_clean();
        
    }
    /**
     * Estrae il log scritti per un determinato intervallo di date
     * @param String $logName  il nome del log senza l'estensione
     * @param Integer $startDate  La data scritta in Intero YYYYMMGGHHMMSS
     * @param Integer $endDate   La data scritta in Intero YYYYMMGGHHMMSS
     */
    function load($logName, $startDate, $endDate) {
        if ($logName == null || $startDate == null || $endDate == null) {
            return array('error'=>'DATA','data_example'=>'&startDate=20100101000000&endDate=20500101000000&logName=system');
        } else {
            $log = Gp::log()->load($logName, $startDate, $endDate);
            return $log;
        }
    }
}