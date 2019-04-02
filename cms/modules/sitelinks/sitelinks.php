<?php 
function module_sitelinks($item, $returnType) {
    $data = array(
        '/index.php?page=api&view=GpDBMySql'=>'Connettersi al database',
        '/index.php?page=api&view=GpListener'=>'Eventi',
        '/index.php?page=api&view=GpRegistry' =>'la gestione dei dati',
        '/index.php?page=api&view=GpRouter'=>'I link e l\'url rewrite',
        '/index.php?page=api&view=GpLoad'=>'I percorsi dei file nel sito',
        '/index.php?page=api&view=moduli'=>'Caricamento dei moduli',
    '/index.php?page=api&view=files'=>'La struttura del cms');

    switch ($returnType) {
        case 'help':
            ob_start();
            require (dirname(__FILE__)."/help.php");
            return ob_get_clean();
            break;
        case 'html':
            ob_start();
            require (dirname(__FILE__)."/html.php");
            return ob_get_clean();
            break;
        case 'array':
            return $data;
            break;
    }
    return false;
}