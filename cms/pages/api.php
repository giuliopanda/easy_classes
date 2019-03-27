<?php
$load = GPLoad::getInstance();
$load->setPath('api', 'pages/api', 'themes/easy/api/pages');
$request = GPRegistry::getInstance()->get('request');

$data = array('view'=>GPRegistry::getInstance()->get('request.view', 'home'));
GPRegistry::getInstance()->set('dataTmpl.content',  $load->module('staticcontent','', $data));
GPRegistry::getInstance()->set('dataTmpl.navbar', $load->module('menu', 'array'));
// Stampo il template
$load->require('theme', 'index.php', 'dataTmpl', false, 'item');
