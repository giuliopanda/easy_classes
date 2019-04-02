<h1>Test Router</h1>
<p>Prima di avviare il test rinomina il file in index.php</p>
<?php
require_once('easy_classes.php');
require_once('router.php');
GpRegistry::getInstance()->set('config.htaccess', true);

$router = GpRouter::getInstance();
$router->setFnRewrite('routerBuild', 'routerParse');
$parse = $router->parseUrl();
echo ("<h3>PAGINA CARICATA: ".$parse['page']."</h3>");

$arrayLinks = array(
    '/index.php?page=pagina1&view=default'=>'PAGINA 1',
    '/index.php?page=pagina1&view=default&id=34'=>'PAGINA 1 bis',
    '/index.php?page=pagina2' =>'PAGINA 2',
    '/index.php?page=pagina3'=>'PAGINA 3');

echo "<h3>Menu</h3>";
foreach ($arrayLinks as $key=>$value) {
    if ($router->isActive($key, array('page', 'view'))) {
        echo "<b>".$value."</b> "; 
    } else {
        echo "<a href=\"".$router->getLink($key)."\">".$value."</a> "; 
    }
}

echo "<h3>LINK BASE</h3>";
echo "<p>".$router->getCurrentLink()."</p>";
echo "<p>getSite: ".$router->getSite()."</p>";
echo "<p>DIRECTORY: ".$router->getDir()."</p>";
echo "<h3>PARSEURL</h3>";
echo "<p>";
$r = ($router->parseUrl());
var_dump($parse);
echo "</p>";



