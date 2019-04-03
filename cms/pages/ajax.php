<?php
$load = GpLoad::getInstance();
Gp::data()->set('request.ajax','1');
$request = Gp::data()->get('request');
$action = Gp::data()->get('request.action', '');
$data = $load->module($request['id'], $action, 'request');
if (is_array($data) || is_object($data)) {
    echo json_encode($data);
} else {
    echo $data;
} 
Gp::db()->close();
exit();