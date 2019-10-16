<?php 
class module_menu
{
    function getMenuData() {
        
        $data =  array(
        '/index.php?page=api&id=GpDBMySql&view=new'=>'Classe Connettersi al database',
        '/index.php?page=api&id=GpListener'=>'Classe Eventi',
        '/index.php?page=api&id=GpRegistry' =>'Classe la gestione dei dati',
        '/index.php?page=api&id=GpRouter'=>'Classe I link e l\'url rewrite',
        '/index.php?page=api&id=GpLoad'=>'Classe I percorsi dei file nel sito',
        '/index.php?page=api&id=GpLog'=>'Classe gestione dei log',
        '/index.php?page=api&id=moduli'=>'CMS Caricamento dei moduli',
        '/index.php?page=api&id=event'=>'CMS Elenco degli eventi',
        '/index.php?page=api&id=files'=>'CMS La struttura dei file e cartelle',
        '/index.php?page=api&id=template'=>'CMS i template');
        $data = Gp::action()->invoke("module_menu_get_data", $data);
        return $data;
    }

    function html() {
        $items = $this->getMenuData();
        ob_start();
        require(dirname(__FILE__)."/html.php");
        return ob_get_clean();
    }
}