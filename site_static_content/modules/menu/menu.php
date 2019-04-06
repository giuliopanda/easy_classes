<?php 
/*
function module_menu($item, $returnType) {
    $data = array(
        '/index.php?page=api&id=GpDBMySql&view=new'=>'Connettersi al database',
        '/index.php?page=api&id=GpListener'=>'Eventi',
        '/index.php?page=api&id=GpRegistry' =>'la gestione dei dati',
        '/index.php?page=api&id=GpRouter'=>'I link e l\'url rewrite',
        '/index.php?page=api&id=GpLoad'=>'I percorsi dei file nel sito',
        '/index.php?page=api&id=moduli'=>'Caricamento dei moduli',
    '/index.php?page=api&id=files'=>'La struttura del cms');

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
*/

class module_menu 
{
    function array($item) {
        return array(
        '/index.php?page=api&id=GpDBMySql&view=new'=>'Connettersi al database',
        '/index.php?page=api&id=GpListener'=>'Eventi',
        '/index.php?page=api&id=GpRegistry' =>'la gestione dei dati',
        '/index.php?page=api&id=GpRouter'=>'I link e l\'url rewrite',
        '/index.php?page=api&id=GpLoad'=>'I percorsi dei file nel sito',
        '/index.php?page=api&id=moduli'=>'Caricamento dei moduli',
    '/index.php?page=api&id=files'=>'La struttura del cms');
    }
}
