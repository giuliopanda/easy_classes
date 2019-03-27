<div class="gp-doc-container gp-content">
<?php
$data = GPRegistry::getInstance();
echo "<h3>GP REGISTRY</h3>";
echo "<p>Memorizza i dati in un singleton così da poterli riavere dove si vuole</p>";
?><pre>$data->set( 'site_name', "Nome del sito");
echo ($data->get('site_name'));
echo ($data->get('site_desc'," - NO DESC"));
</pre>

<div class="row">
    <div class="col">
    <p><b>Risultato:</b></p>
    <?php
    $data->set( 'site_name', "Nome del sito");
    echo ($data->get('site_name'));
    echo ($data->get('site_desc'," - descrizione di default"));
    ?>
    </div>
    <div class="col">
    <p><b>Risultato atteso:</b></p>
    Nome del sito - descrizione di default

    </div>
</div>

<?php
echo "<h3>REQUEST GET e POST</h3>";
?><pre>var_dump ($data->get('request'));</pre>

<div class="row">
    <div class="col-6">
    <p><b>Risultato:</b></p>
    <?php  
        var_dump ($data->get('request'));
    ?>
    </div>
    <div class="col-6">
        <p><b>Risultato atteso:</b></p>
<pre class="xdebug-var-dump" dir="ltr"><small>/var/www/html/easy_classes/cms/assets/staticcontent/gpRegistry-test.php:xx:</small>
<b>array</b> <i>(size=2)</i>
    'page' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'api'</font> <i>(length=3)</i>
    'view' <font color="#888a85">=&gt;</font> <small>string</small> <font color="#cc0000">'gpRegistry-test'</font> <i>(length=15)</i>
</pre>

    </div>
</div>






<h3>ARRAY</h3>
<pre>$data->set( 'access', array("ip"=>"localhost","user"=>"admin", "psw"=>"admin"));
echo "&lt;p&gt;getSite: ".$data->get('access.ip')."&lt;/p&gt;";</pre><?php
$data->set( 'access', array("ip"=>"localhost","user"=>"admin", "psw"=>"admin"));
echo "<p>getSite: ".$data->get('access.ip')."</p>";

echo "<h3>Verifica l'esistenza di una variabile</h3>";
if ($data->has ('access')) {
    echo "<p>la variabile access esiste</p>";
} else {
    echo "<p>la variabile Access non esiste</p>"; 
}
if ($data->has ('sbirulo')) {
    echo "<p>la variabile sbirulo esiste</p>";
} else {
    echo "<p>la variabile sbirulo non esiste</p>"; 
}

echo "<h3>Ciclo un array</h3>";

while ( list($key, $currentData) = $data->for('access') ) {
    echo  ("<p>".$key." > ".$currentData."</p>");
}

echo "<h3>Ciclo un array e esco dal ciclo</h3>";

while ( list($key, $currentData) = $data->for('access') ) {
    var_dump ($currentData);
    $data->break();
}


echo "<p>E' possibile aggiungere ad un array dei valori numerici (<b>\$data->set('country.[]', 'italia');</b>:</p>";
$data->set('country.[]', 'italia');
$data->set('country.Francia', 'Francia');
$data->set('country.[]', 'Spagna');
var_dump ($data->get('country')); // è case insensitive
$data->set('country.0', null);
var_dump ($data->get('country'));

$data->set('access', null);
var_dump ($data->get('access'));

echo "<h3>OBJECT</h3>";
$obj = new StdClass();
$obj->title = "MY TITLE";
$obj->author = "admin";
$obj->text = "lorem ipsum dolor sit amet";
$data->set( 'article', (array)$obj);
echo "<p>getSite: ".$data->get('article.title')."</p>";




echo "<h3> BUILD ritorna una stringa dopo aver fatto passare i dati ad una funzione</h3>";

echo $data->build('article', 'drawArticle');


echo "<h3>SESSIONI</h3>";
echo "<p><a href=\"?myvar=222&myData[email]=g@m.it&myData[psw]=prova1\">?myvar=222&myData[email]=g@m.it&myData[psw]=prova1</a></p>";
echo "<p><a href=\"?myvar=222&myData[email]=g@m.com&myData[psw]=qwerty\">?myvar=222&myData[email]=g@m.com&myData[psw]=qwerty</a></p>";
var_dump ($data->get('session.mydata'));
var_dump ($data->requestQueue("mydata"));



function drawArticle($data) {
    echo "<div style=\"background:#F2F2F2; border:1px solid #CCC; padding:10px; margin:10px;\">";
    echo "<h2>".$data->get('title')."</h2>";
    echo "<p>".$data->get('text')."</p>";
    echo "</div>";
}

?>
</div>