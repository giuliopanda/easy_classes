<?php 
function module_menu($item, $action) {
    $data = array(
        '/index.php?page=api&id=GpDBMySql'=>'Connettersi al database',
        '/index.php?page=api&id=GpListener'=>'Eventi',
        '/index.php?page=api&id=GpRegistry' =>'la gestione dei dati',
        '/index.php?page=api&id=GpRouter'=>'I link e l\'url rewrite',
        '/index.php?page=api&id=GpLoad'=>'I percorsi dei file nel sito',
        '/index.php?page=api&id=moduli'=>'Caricamento dei moduli',
    '/index.php?page=api&id=files'=>'La struttura del cms');

    return $data;
}