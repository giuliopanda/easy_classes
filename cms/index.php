<?php
session_start();
$dir = dirname(__FILE__);
$config = array();
$config['cmsDir'] = "cms";
$config['siteDir'] = "site";
$config['template'] = "easy";
$config['htaccess'] = true;
// il framework
require_once($dir."/".$config['cmsDir'].'/classes/easy_classes.php');
// Il gestore delle pagine da caricare
require_once($dir."/".$config['cmsDir'].'/classes/gpload.php');

$load = GPLoad::getInstance();
$load->setPath('cms', $config['cmsDir']);
$load->setPath('site', $config['siteDir']);
$load->setPath('theme', $config['siteDir'].'/themes/'.$config['template']);
$load->setPath('pages', array($config['siteDir'].'/pages', $config['cmsDir'].'/pages'));
$load->setPath("_modules", array($config['siteDir'].'/modules', $config['cmsDir'].'/modules'));
$load->setPath("assets", array($config['siteDir'].'/assets', $config['cmsDir'].'/assets'));

// le funzioni che definiscono come si deve fare il parsing dei link
$load->require("assets", "router.php");
GPRegistry::getInstance()->set('config', $config);
$load->require("assets", "config.php");
//require_once($dir."/".$config['cmsDir'].'/assets/router.php');

// Setto il router

$router = gpRouter::getInstance();
$router->setFnRewrite('routerBuild', 'routerParse');
// setto i percorsi delle pagine

// Faccio il parsing della pagina
$parse = $router->parseUrl();
$query = $parse['query'];
GPRegistry::getInstance()->set('request', $query);

$load->require('assets', "function.php");

// se il link punta ad una pagina o ad un file lo carico?
// $realPath = $router->linkToDir(); ??

// Carico il contenuto della pagina che si trova in pages
if (!array_key_exists('page',$query) || in_array(strtolower($query['page']), array("index","home", "index.php","index.html"))) {
    $load->require('pages', "home.php");
} else if (!$load->require('pages', $query['page'].".php")) {
    $load->require('pages', "404.php");
}
