<?php
session_start();
require_once('easy_classes.php');
require_once('router.php');

GPRegistry::getInstance()->set('config.htaccess', false);
$template = 'easy';
GPRegistry::getInstance()->set('config.template', 'easy');

/**
 * SERVE PER CARICARE LE VARIE PARTI DEL SITO E FARE EVENTUALI OVERRIDE
 */
class GPLoad
{
   /*
	 * @var 		GPLoad  	L'istanza della classe per il singleton
	*/
	private static $instance 	= null;
	/*
	 * @var    link     I link del sito
	*/
    private $link = array();
    /*
	 * @var    dir      Le directory del sito
	*/
	private $dir = array();
	/**
	 * Ritorna il singleton della classe
	 * @return  	singleton GPLoad
	**/
	public static function getInstance()
	{
   	   if(self::$instance == null)
	   {
   	      $c = __CLASS__;
   	      self::$instance = new $c;
		}
		return self::$instance;
    }
}



$router = gpRouter::getInstance();
$router->setFnRewrite('routerBuild', 'routerParse');

echo $router->getLink('themes/'.$template);



$parse = $router->parseUrl();
$query = $parse['query'];
if (!array_key_exists('page',$query) || in_array(strtolower($query['page']), array("index","home"))) {
    require_once($router->getDir()."pages/home.php");
} else if (is_file($router->getDir()."pages/".$query['page'].".php")) {
    require_once($router->getDir()."pages/".$query['page'].".php");
} else {
    require_once($router->getDir()."/pages/404.php");
}