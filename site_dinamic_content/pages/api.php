<?php
$load = GpLoad::getInstance();
$load->setPath('api', $load->get('cms').'/pages/api', $load->get('theme').'/pages/api');
$request = GpRegistry::getInstance()->get('request');
$data = array('pageName'=>GpRegistry::getInstance()->get('request.id', 'home'));
GpRegistry::getInstance()->set('dataTmpl.content',  $load->module('staticcontent','html', $data));
GpRegistry::getInstance()->set('dataTmpl.navbar', $load->module('menu', 'array'));
// Stampo il template
$load->require('theme', 'index.php', 'dataTmpl', false);
