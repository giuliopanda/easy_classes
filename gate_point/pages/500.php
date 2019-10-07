<?php 
$cData->set('content', "<h1>500 C'è stato un errore nella pagina</h1>". Gp::load()->module('logsystem', 'getCurrentPage', array('logType'=>'system') ));
// Stampo il template
if (Gp::load()->getPath('theme', 'index.php')) {
    Gp::load()->require('theme', 'index.php', $cData, false);
} else {
    print $cData->get('content');
}