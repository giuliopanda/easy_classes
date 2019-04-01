<?php
$load = GPLoad::getInstance();
$request = GPRegistry::getInstance()->get('request');
$data = array('view'=>'home');
GPRegistry::getInstance()->set('dataTmpl.content',  $load->module('staticcontent','', $data));
GPRegistry::getInstance()->set('dataTmpl.navbar', $load->module('menu', 'array'));
// Stampo il template
$load->require('theme', 'index.php', 'dataTmpl', false);