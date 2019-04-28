<?php 

class module_staticcontent
{
    function html($pageName = "") {
        if (!Gp::load()->issetPath('staticcontent')) {
            Gp::load()->append('staticcontent', 'assets', 'staticcontent');
        }
        ob_start();
        ?><div class="" <?php echo Gp::log()->getDataLog(); ?>><?php
        Gp::load()->require('staticcontent', $pageName.'.php');
        ?></div><?php
        return ob_get_clean();
    }
}