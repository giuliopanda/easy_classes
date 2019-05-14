<?php 
require_once(dirname(__FILE__)."/model.php");
class module_sitelinks {
    //
    var $model;
    function __construct() {
        $this->model = new model_sitelinks();
    }
    //
    function help() {
        ob_start();
        require(dirname(__FILE__)."/help.php");
        return ob_get_clean();
    }
    //
    function getPageFromLink($parsePage) {
        $page = "";
        $newPage = $this->model->getPageFromLink($parsePage);
        if ($newPage['page'] != false) {
            $page = $newPage;
        }
        return $page;
    }
    //
    function getLinkFromPage($parsePage, $parseView) {
        $page = "";
        $newPage = $this->model->getLinkFromPage($parsePage, $parseView);
         if ($newPage['page'] != false) {
            $page = $newPage;
        }
        return $page;
    }
}
