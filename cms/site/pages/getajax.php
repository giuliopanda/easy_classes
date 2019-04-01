<?php
$load = GPLoad::getInstance();
$request = GPRegistry::getInstance()->get('request');
$type = GPRegistry::getInstance()->get('request.type', 'html');

switch ($type) {
    case 'json' : 
        $data = $load->module($request['module'], 'array', 'request');
        echo json_encode($data);
        break;
    default : 
        $data = $load->module($request['module'], 'html', 'request');
        echo $data;
        break;
}