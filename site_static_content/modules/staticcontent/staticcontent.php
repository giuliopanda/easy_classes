<?php 
function module_staticcontent($item, $returnType) {
    $load = GpLoad::getInstance();
    $load->setPath('staticcontent', $load->get('site').'/assets/staticcontent');
    $page = GpRegistry::getInstance()->get('request.view', 'home');
    $page = $item->get('view', $page);
    switch ($returnType) {
        case 'help':
            ob_start();
            require (dirname(__FILE__)."/help.php");
            return ob_get_clean();
            break;
        case 'array':
            return $data;
            break;
        case 'html':
        default:
            ob_start();
            
            $load->require('staticcontent', $page.'.php');
            return ob_get_clean();
            break;
    }
    return false;
}