<?php 
Gp::load()->setPath('staticcontent', Gp::load()->get('site').'/assets/staticcontent');
class module_staticcontent
{
    function html($pageName = "") {
        ob_start();
        Gp::load()->require('staticcontent', $pageName.'.php');
        return ob_get_clean();
    }
}