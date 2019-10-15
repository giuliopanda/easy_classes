<div class="gp-doc-container gp-content">
    <?php
    $router = new GpRouter('/var/www/html/easy_classes/cms/api' , $scheme = "http", "www.miosito.it");
?>
<h3>Impostazione di un nuovo rooter</h3>
<pre> $router = new GpRouter('/var/www/html/easy_classes/cms/api' , $scheme = "http", "www.miosito.it");
</pre>

<h3>LINK BASE</h3>
<pre> echo $router->getCurrentLink();
echo "getSite: ".$router->getSite();
echo "DIRECTORY: ".$router->getDir();
</pre>
<div class="row php-test">
    <div class="col">
    <p><b>Risultato:</b></p>
        <?php
        echo "<p>".$router->getCurrentLink()."</p>";
        echo "<p>getSite: ".$router->getSite()."</p>";
        echo "<p>DIRECTORY: ".$router->getDir()."</p>";
        ?>
    </div>
    <div class="col">
    <p><b>Risultato atteso:</b></p>
    <p>http:[site_name]/api/GpRouter-test</p>
    <p>getSite: http://www.miosito.it</p>
    <p>DIRECTORY: /[path]/</p>
    </div>
</div>
<h3>PARSING</h3>
<pre>$r = ($router->parseUrl());
var_dump($r);
</pre>
<div class="row php-test">
    <div class="col">
    <p><b>Risultato:</b></p>
        <?php
        $r = ($router->parseUrl());
        var_dump($r);
        ?>
    </div>
    <div class="col">
    <p><b>Risultato atteso:</b></p>
    <pre class="xdebug-var-dump" dir="ltr"><small>...</small>

<b>array</b> <i>(size=4)</i>
  'scheme' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'http'</font> <i>(length=4)</i>
  'host' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'localhost'</font> <i>(length=9)</i>
  'path' <font color="#888a85">=&gt;</font> 
    <b>array</b> <i>(size=4)</i>
      0 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'project'</font> <i>(length=7)</i>
      1 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'github'</font> <i>(length=6)</i>
      2 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'easy_classes'</font> <i>(length=12)</i>
      3 <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'index.php'</font> <i>(length=9)</i>
  'query' <font color="#888a85">=&gt;</font> 
    <b>array</b> <i>(size=2)</i>
      'page' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'api'</font> <i>(length=3)</i>
      'id' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'GpRouter-test'</font> <i>(length=13)</i>

</pre>
    </div>
</div>

<h3>COSTRUZIONE DEI LINK SENZA l'URL REWRITE</h3>
<pre> echo ($router->getLink('index.php?page=api')); 
 echo ($router->getLink('index.php?page=api&id=sp')); 
 echo ($router->getLink('index.php?page=api&id=sp&id=32')); 
 echo ($router->getLink('pippo/index.php?id=22'));  //[NON PRENDE IL PERCORSO!]
 echo ($router->getLink('pippo/index.php?id=22&id=so')); //[SOSTITUISCE LA VIEW ALLA PAGE!]
</pre>
<div class="row php-test">
    <div class="col">
    <p><b>Risultato:</b></p>
        <?php echo ($router->getLink('index.php?page=api')); ?><br>
        <?php echo ($router->getLink('index.php?page=api&id=sp')); ?><br>
        <?php echo ($router->getLink('index.php?page=api&id=sp&id=32')); ?><br>
        <?php echo ($router->getLink('pippo/index.php?id=22')); ?> <br>
        <?php echo ($router->getLink('pippo/index.php?id=22&id=so')); ?> <br>
    </div>
    <div class="col">
    <p><b>Risultato atteso:</b></p>
        http://www.miosito.it/index.php?page=api<br>
        http://www.miosito.it/index.php?page=api&id=sp<br>
        http://www.miosito.it/index.php?page=api&id=sp&id=32<br>
        http://www.miosito.it/pippo/index.php?id=22 <br>
        http://www.miosito.it/pippo/index.php?id=22&id=so <br>
    </div>
</div>
<?php
function rBuild($query, $routerClass) {
    if (GpRegistry::getInstance()->get('config.htaccess', true)) {
        $parse = $routerClass->parseUrl($query, false);
      
        $query = $routerClass->queryToPath($parse, "page", "view");
        
    }
    return $query;
}
function rParse($parseUrl, $routerClass) {
    return $routerClass->pathToQuery($parseUrl, "page", "view");
}
$router->setFnRewrite('rBuild', 'rParse');
?>
<h3>COSTRUZIONE DEI LINK CON l'URL REWRITE</h3>
<pre> 
function rBuild($query, $routerClass) {
    if (GpRegistry::getInstance()->get('config.htaccess', true)) {
        $parse = $routerClass->parseUrl($query, false);
        $query = $routerClass->queryToPath($parse, "page", "view");
    }
    return $query;
}
function rParse($parseUrl, $routerClass) {
    return $routerClass->pathToQuery($parseUrl, "page", "view");
}
$router->setFnRewrite('rBuild', 'rParse');
echo ($router->getLink('index.php?page=api')); 
echo ($router->getLink('index.php?page=api&id=sp')); 
echo ($router->getLink('index.php?page=api&id=sp&id=32')); 
echo ($router->getLink('pippo/index.php?id=22'));  //[Non lo converte perché il path è già impostato]
echo ($router->getLink('/index.php?id=22&id=so')); //[Non lo converte perché manca page!]
</pre>
    <div class="row php-test">
        <div class="col">
        <p><b>Risultato:</b></p>
            <?php echo ($router->getLink('index.php?page=api')); ?><br>
            <?php echo ($router->getLink('index.php?page=api&id=sp')); ?><br>
            <?php echo ($router->getLink('index.php?page=api&id=sp&id=32')); ?><br>
            <?php echo ($router->getLink('pippo/index.php?id=22')); ?> <br>
            <?php echo ($router->getLink('index.php?id=22&id=so')); ?> <br>
        </div>
        <div class="col">
        <p><b>Risultato atteso:</b></p>
            http://www.miosito.it/api<br>
            http://www.miosito.it/api/sp<br>
            http://www.miosito.it/api/sp?id=32<br>
            http://www.miosito.it/pippo/index.php?id=22 <br>
            http://www.miosito.it/?id=22&id=so <br>
        </div>
    </div>
</div>
