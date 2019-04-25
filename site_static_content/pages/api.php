<?php
$load = GpLoad::getInstance();
//Gp::load()->append('staticcontent', 'assets', 'staticcontent');
$data = array('pageName'=>Gp::data()->get('request.id', 'home'));
$cData->set('content',  $load->module('staticcontent','html', $data));
$cData->set('navbar.[]', $load->module('menu', 'html'));
// Stampo il template
$load->require('theme', 'index.php', $cData, false);
