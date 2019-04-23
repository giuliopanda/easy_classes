<?php
$listener = Gp::action();

function afterRis($result) {
    $result['/'] = 'Home page';
    return $result;
}
$listener->add('module_menu_get_data', 'afterRis');

// test di passaggio dei dati negli eventi
