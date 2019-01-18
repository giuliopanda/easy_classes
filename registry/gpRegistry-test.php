<?php
session_start();
require_once('gpRegistry.php');
$data = GPRegistry::getInstance();
echo "<h3>GP REGISTRY</h3>";
echo "<p>Memorizza i dati in un singleton così da poterli riavere dove si vuole</p>";
$data->set( 'site_name', "Nome del sito");
echo ($data->get('site_name'));
echo ($data->get('site_desc'," - NO DESC"));

echo "<h3>REQUEST GET e POST</h3>";
var_dump ($data->get('request'));

echo "<h3>ARRAY</h3>";
$data->set( 'access', array("ip"=>"localhost","user"=>"admin", "psw"=>"nimda"));
echo "<p>getSite: ".$data->get('access.ip')."</p>";


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
$data->set( 'article', $obj);
echo "<p>getSite: ".$data->get('article.title')."</p>";

echo "<h3>SESSIONI</h3>";
echo "<p><a href=\"?myvar=222&myData[email]=g@m.it&myData[psw]=prova1\">?myvar=222&myData[email]=g@m.it&myData[psw]=prova1</a></p>";
echo "<p><a href=\"?myvar=222&myData[email]=g@m.com&myData[psw]=qwerty\">?myvar=222&myData[email]=g@m.com&myData[psw]=qwerty</a></p>";
var_dump ($data->get('session.mydata'));
var_dump ($data->requestQueue("mydata"));
