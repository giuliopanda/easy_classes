<?php
session_start();
require_once('easy_classes.php');
require_once('gpload.php');
require_once('router.php');
// Setto il router
GPRegistry::getInstance()->set('config.htaccess', false);
$router = gpRouter::getInstance();
$router->setFnRewrite('routerBuild', 'routerParse');
// setto i percorsi delle pagine
$load = GPLoad::getInstance();
$load->setPath('theme', 'themes/easy');
$load->setPath('pages', 'pages', 'themes/easy/pages');
// eseguo il core del sito
$parse = $router->parseUrl();
$query = $parse['query'];
if (!array_key_exists('page',$query) || in_array(strtolower($query['page']), array("index","home"))) {
    $load->require('pages', "home.php");
} else if (!$load->require('pages', $query['page'].".php")) {
    $load->require('pages', "404.php");
}
