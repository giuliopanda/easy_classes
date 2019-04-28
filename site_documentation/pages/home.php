<?php
$load = GpLoad::getInstance();
$request = GpRegistry::getInstance()->get('request');
$data = array('pageName'=>'home');
$cData->set('content', $load->module('staticcontent','html', array('pageName'=>'home')));
$cData->set('header', $load->module('header', 'html'));
$cData->set('navbar.[]', $load->module('menu', 'html'));
//$cData->set('navbar2', $load->module('menuk', 'html')); // questo non esiste
$cData->set('footer', $load->module('logsystem', 'getCurrentPage', array('logType'=>'system')));
// Stampo il template
$load->require('theme', 'index.php', $cData, false);