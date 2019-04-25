<?php
Gp::data()->set('config.template', "easy");
Gp::data()->set('config.htaccess', false);
/*
Gp::data()->set('config.dbaccess', array(
    'ip' => "localhost",
    'name' => "admin",
    'psw' => "admin",
    'dbName' => "test_class",
    'prefix' => "gp"
));
*/
// configuro i log:
Gp::data()->set('config.log.size', 1024*3); // la dimensione i un log in kb prima di ruotarli
Gp::data()->set('config.log.max_files', '3');// Il numero massimo di log prima di cancellarli
Gp::data()->set('config.log.write_error', true); // Quando scrivere il log del sistem TRUE|FALSE