<?php
require_once('../gpRouter.php');
$router = gpRouter::getInstance();
$router->setFnRewrite('myrouterBuild', 'myrouterParse');
echo "<h3>Menu</h3>";
echo "<a href=\"".$router->getLink('/index.php?page=pagina1')."\">Pagina1</a> ";
echo "<a href=\"".$router->getLink('/index.php?page=pagina2')."\">Pagina2</a> ";
echo "<a href=\"".$router->getLink('/index.php?page=pagina3')."\">Pagina3</a> ";

$parse = $router->parseUrl();
echo ("<h3>PAGINA CARICATA: ".$parse['page']."</h3>");



echo "<h3>LINK BASE</h3>";
echo "<p>".$router->getCurrentLink()."</p>";
echo "<p>getSite: ".$router->getSite()."</p>";
echo "<p>DIRECTORY: ".$router->getDir()."</p>";
echo "<h3>PARSEURL</h3>";
echo "<p>";
$r = ($router->parseUrl());
var_dump($parse);
echo "</p>";




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

