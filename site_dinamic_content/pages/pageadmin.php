<?php
$load = GpLoad::getInstance();
$cData->set('header', $load->module('header', 'html'));
$cData->set('content', $load->module('pages.admin','chooseCommand', array('cmd'=>Gp::data()->get('request.cmd'))));
$cData->set('footer', $load->module('logsystem', 'getCurrentPage', array('logType'=>'system')));

// Stampo il template

$load->require('theme', 'index.php', $cData, false);