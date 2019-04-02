<?php
$load = GpLoad::getInstance();
$request = GpRegistry::getInstance()->get('request');
$data = array('view'=>'home');
GpRegistry::getInstance()->set('dataTmpl.content',  $load->module('staticcontent','', $data));
GpRegistry::getInstance()->set('dataTmpl.navbar', $load->module('menu', 'array'));
// Stampo il template
$load->require('theme', 'index.php', 'dataTmpl', false);