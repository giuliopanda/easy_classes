<?php
session_start();
// il framework
require_once('easy_classes.php');
// Il gestore delle pagine da caricare
require_once('gpload.php');
// le funzioni che definiscono come si deve fare il parsing dei link
require_once('router.php');
// Setto il router
GPRegistry::getInstance()->set('config.htaccess', true);
$router = gpRouter::getInstance();
$router->setFnRewrite('routerBuild', 'routerParse');
// setto i percorsi delle pagine
$load = GPLoad::getInstance();
$load->setPath('theme', 'themes/easy', 'themes/easy/override');
$load->setPath('pages', 'pages', 'themes/easy/override/pages');

// Faccio il parsing della pagina
$parse = $router->parseUrl();
$query = $parse['query'];
GPRegistry::getInstance()->set('request', $query);
$load->require('theme', "function.php");
// Carico il contenuto della pagina che si trova in pages
if (!array_key_exists('page',$query) || in_array(strtolower($query['page']), array("index","home"))) {
    $load->require('pages', "home.php");
} else if (!$load->require('pages', $query['page'].".php")) {
    $load->require('pages', "404.php");
}