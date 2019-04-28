<?php 
class module_header
{
    function html() {
        ob_start();
        ?>
        <header class="osp-header ecs-clearfix" <?php echo Gp::log()->getDataLog(); ?>>
            <div class="osp-col-header-left">
            <div class="ecs-only-cell" id="burderMenu" data-target="#sidebar">
                <svg class="osp-search"><use xlink:href="#svgBurgerMenu"></use></svg>
            </div>
            <div class="osp-logo"><?php echo Gp::data()->get('site.title', 'EASY_CLASSES'); ?></div>
            </div>
            <div class="osp-col-header-center"></div>
            <div class="osp-col-header-right"></div>
        </header>
        <?php
        return ob_get_clean();
    }
}