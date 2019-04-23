<?php
session_start();
$dir = dirname(__FILE__);
$config = array();
$config['cmsDir'] = "gate_point";
$config['siteDir'] = "site_static_content";
//$config['siteDir'] = "site_dinamic_content";
$config['template'] = "easy";
$config['htaccess'] = true;
// il framework
require_once($dir."/".$config['cmsDir'].'/classes/Gp.php');

// SETTO I PERCORSI DELLE CARTELLE
$load = Gp::load();
$load->setPath('cms', $config['cmsDir']);
$load->setPath('site', $config['siteDir']);
$load->setPath('theme', 'themes/'.$config['template']);
$load->setPath('pages', array($config['siteDir'].'/pages', $config['cmsDir'].'/pages'));
$load->setPath("_modules", array($config['siteDir'].'/modules', $config['cmsDir'].'/modules'));
$load->setPath("assets", array($config['siteDir'].'/assets', $config['cmsDir'].'/assets'));

// le funzioni che definiscono come si deve fare il parsing dei link
$load->require("assets", "router.php");
Gp::data()->set('config', $config);
$load->require("assets", "config.php");

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

/*
$log = Gp::log()->load('system_20190417144647', 20190417140000, 20190417144000);
echo "LOGS: ".count ($log);
*/
