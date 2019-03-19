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
$load->setPath('theme', 'themes/easy');
$load->setPath('pages', 'pages', 'themes/easy/pages');
// Faccio il parsing della pagina
$parse = $router->parseUrl();
$query = $parse['query'];
// Carico il contenuto della pagina che si trova in pages
ob_start();
if (!array_key_exists('page',$query) || in_array(strtolower($query['page']), array("index","home"))) {
    $load->require('pages', "home.php");
} else if (!$load->require('pages', $query['page'].".php")) {
    $load->require('pages', "404.php");
}
GPRegistry::getInstance()->set('regTemplate.content', ob_get_clean());
// Imposto la sidebar con l'array dei link
$arrayLinks = array(
    '/index.php?page=gpDBMySql'=>'Connettersi al database',
    '/index.php?page=gpListener'=>'Eventi',
    '/index.php?page=gpRegistry' =>'la gestione dei dati',
    '/index.php?page=gpRouter'=>'I link e l\'url rewrite',
    '/index.php?page=gpLoad'=>'I percorsi dei file nel sito');
GPRegistry::getInstance()->set('regTemplate.navbar', $arrayLinks);
// Stampo il template
echo GPRegistry::getInstance()->build('regTemplate', function($item) {
    if ($template = GPLoad::getInstance()->getPath('theme','index.php')) {
        require($template);
    }
});