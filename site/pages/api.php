<?php
$load = GpLoad::getInstance();
$load->setPath('api', $load->get('cms').'/pages/api', $load->get('theme').'/pages/api');
$request = GpRegistry::getInstance()->get('request');
$data = array('view'=>GpRegistry::getInstance()->get('request.view', 'home'));
GpRegistry::getInstance()->set('dataTmpl.content',  $load->module('staticcontent','', $data));
GpRegistry::getInstance()->set('dataTmpl.navbar', $load->module('menu', 'array'));
// Stampo il template
$load->require('theme', 'index.php', 'dataTmpl', false);
