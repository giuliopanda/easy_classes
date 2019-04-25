<?php
// SETTO I PERCORSI DELLE CARTELLE
$load = Gp::load();
$load->setPath('cms', $config['cmsDir']);
$load->setPath('site', $config['siteDir']);
$load->setPath('themes', array( $config['siteDir'].'/themes', 'themes') ) ;
$load->setPath('pages', array($config['siteDir'].'/pages', $config['cmsDir'].'/pages'));
$load->setPath("_modules", array($config['siteDir'].'/modules', $config['cmsDir'].'/modules'));
$load->setPath("assets", array($config['siteDir'].'/assets', $config['cmsDir'].'/assets'));



// GESTIONE DEI FATAL ERROR
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
            Gp::log()->set('system', 'ERROR', "FATAL ERROR: ".$msg, false, array($errfile.":".$errline));
            Gp::log()->write('error', 'FATALERROR', $msg, false, array($errfile.":".$errline));
        }
      
        Gp::action()->invoke("logOnFatalHandler", $error );
        Gp::load()->require('pages', "500.php");
    }
}
