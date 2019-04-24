<?php 
$cData->set('content', "<h1>500 C'è stato un errore nella pagina</h1>". Gp::load()->module('logsystem', 'getCurrentPage', array('logType'=>'system') ));
// Stampo il template
Gp::load()->require('theme', 'index.php', $cData, false);