<?php
$listener = GpListener::getInstance();
function afterRis($result, $cData, $type) {
    $result['/'] = 'Home page';
    return $result;
}
$listener->add('module_menu_event', 'afterRis');