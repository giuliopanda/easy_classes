<?php
session_start();
require_once('easy_classes.php');
require_once('elements_classes.php');
require_once('router.php');
$db = new gpDBMySql('localhost', 'admin', 'admin', 'test_class');
if ($db->error) {
    echo "Error dataBase connect";
    die;
}
GPRegistry::getInstance()->set('db', $db);
GPRegistry::getInstance()->set('config.htaccess', false);
$template = 'easy';
$router = gpRouter::getInstance();
$router->setFnRewrite('routerBuild', 'routerParse');
$parse = $router->parseUrl();
$query = $parse['query'];
if (!array_key_exists('page',$query) || in_array(strtolower($query['page']), array("index","home"))) {
    require_once($router->getDir()."pages/home.php");
} else if (is_file($router->getDir()."pages/".$query['page'].".php")) {
    require_once($router->getDir()."pages/".$query['page'].".php");
} else {
    require_once($router->getDir()."/pages/404.php");
}