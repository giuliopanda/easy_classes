<?php 
Gp::load()->setPath('staticcontent', Gp::load()->get('site').'/assets/staticcontent');
class module_staticcontent
{
    function html($pageName = "") {
        ob_start();
        ?><div class="" <?php echo Gp::log()->getDataLog(); ?>><?php
        Gp::load()->require('staticcontent', $pageName.'.php');
        ?></div><?php
        return ob_get_clean();
    }
}