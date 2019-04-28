<?php
session_start();
$dir = dirname(__FILE__);
$config = array();
$config['cmsDir'] = "gate_point";
$config['siteDir'] = "site_documentation";

// il framework
require_once($dir."/".$config['cmsDir'].'/classes/Gp.php');
require_once($dir."/".$config['cmsDir'].'/init.php');

// SETTO I PERCORSI DELLE CARTELLE
$load = Gp::load();

// le funzioni che definiscono come si deve fare il parsing dei link
$load->require("assets", "router.php");
Gp::data()->set('config', $config);
$load->require("assets", "config.php");
$load->append('theme', 'themes', Gp::data()->get('config.template'));
$ac = Gp::data()->get('config.dbaccess');
if (is_array($ac) && count($ac) > 3) {
    Gp::db()->connect($ac['ip'],$ac['name'],$ac['psw'],$ac['dbName']);
    if (array_key_exists('prefix', $ac)) {
        Gp::db()->setPrefix($ac['prefix']);
    }
}

// Setto il router
$router = Gp::route();
$router->setFnRewrite('routerBuild', 'routerParse');
// setto i percorsi delle pagine

// Faccio il parsing della pagina
$parse = $router->parseUrl();
$query = $parse['query'];
Gp::data()->set('request', $query);

$load->require('assets', "function.php");
// se il link punta ad una pagina o ad un file lo carico?
// $realPath = $router->linkToDir(); ??
// Carico il contenuto della pagina che si trova in pages senza passare per pageInfo
// in questo caso sto caricando il MASTER per cui almeno dovrò avere privilegi di amministratore
ob_start();
if (!array_key_exists('page',$query) || in_array(strtolower($query['page']), array("index","home", "index.php","index.html"))) {
    $load->require('pages', "home.php");
} else if (!$load->require('pages', $query['page'].".php")) {
    $load->require('pages', "404.php");
}
echo Gp::action()->invoke("systemOnAfterRender", ob_get_clean());

if (Gp::data()->get('config.log.write_error', false)) {
    Gp::log()->write('error');
}
