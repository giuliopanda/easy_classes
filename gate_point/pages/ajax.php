<?php
$load = GpLoad::getInstance();
Gp::data()->set('request.ajax','1');
$request = Gp::data()->get('request');
$data = $load->module($request['id'], $request['action'], 'request');
if (is_array($data) || is_object($data)) {
    echo json_encode($data);
} else {
    echo $data;
} 
Gp::db()->close();
exit();