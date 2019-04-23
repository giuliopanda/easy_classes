<?php
$load = GpLoad::getInstance();
$load->setPath('api', $load->get('cms').'/pages/api', $load->get('theme').'/pages/api');
$data = array('pageName'=>Gp::data()->get('request.id', 'home'));
$cData->set('content',  $load->module('staticcontent','html', $data));
$cData->set('navbar.[]', $load->module('menu', 'html'));
// Stampo il template
$load->require('theme', 'index.php', $cData, false);
