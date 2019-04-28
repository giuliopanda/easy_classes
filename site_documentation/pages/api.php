<?php
$load = Gp::load();
$cData->set('header', $load->module('header', 'html'));
$data = array('pageName'=>Gp::data()->get('request.id', 'home'));
$cData->set('content',  $load->module('staticcontent','html', $data));
$cData->set('navbar.[]', $load->module('menu', 'html'));
$load->require('theme', 'index.php', $cData, false);
