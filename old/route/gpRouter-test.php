<?php
require_once('GpRouter.php');
$router = GpRouter::getInstance();
echo "<h3>LINK BASE</h3>";
echo "<p>".$router->getCurrentLink()."</p>";
echo "<p>getSite: ".$router->getSite()."</p>";
echo "<p>DIRECTORY: ".$router->getDir()."</p>";
echo "<h3>PARSEURL</h3>";
echo "<p>";
$r = ($router->parseUrl());
var_dump($r);
echo "</p>";
echo "<h3>COSTRUZIONE DEI LINK</h3>";
$link = $router->getLink('pippo/index.php?link=2&a=oun');
echo "<p>getLink: ".$link ."</p>";


echo "<h3>HTACCESS</h3>";
echo "<p>Genera un link con l'htaccess<br>";
echo "Setto le funzioni di build e parse \$router->setFnRewrite('myrouterBuild', 'myrouterParse');</p>";
//$router->setFnRewrite('myrouterBuild', null);
$router->setFnRewrite('myrouterBuild', 'myrouterParse');
$link = $router->getLink('/index.php?page=mia_pagina&id=23');

echo "<p>\$router->getLink('/index.php?page=mia_pagina&id=23'): ".$link ."</p>";

echo "<p>Oppure faccio il parsing di una url</p>";
$parse = $router->parseUrl($link);
var_dump ($parse);


function myrouterBuild($query, $routerClass) {
   $parse = $routerClass->parseUrl($query);
   if (array_key_exists('page', $parse['query'])) {
       $page = $parse['query']['page'];
       unset($parse['query']['page']);
       return "/".$page.$routerClass->implodeQuery($parse['query']);
   }
}

function myrouterParse($parseUrl, $routerClass) {
    if (count($parseUrl['path']) > 0) {
        $parseUrl['page'] = array_shift($parseUrl['path']);
    }
    //pathQuery
    return $parseUrl;
}

