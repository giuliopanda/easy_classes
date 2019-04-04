<?php 
/** Questo modulo permette il rewrite secondo i dati inseriti nel database! */
require_once(dirname(__FILE__)."/model.php");
function module_sitelinks($item, $action) {
    $model = new model_sitelinks();
    switch ($action) {
        case 'getPageFromLink':
            $p = ($item->has('p') && Gp::data()->get('request.ajax','0') == 1) ? $item->get('p') : $item->get('page');
            if ($p != "") {
                $page = $model->getPageFromLink($p);
            } else {
                $page = false;
            }
            return $page;
            break;
        case 'getLinkFromPage':
            $p = ($item->has('p') && Gp::data()->get('request.ajax','0') == 1) ? $item->get('p') : $item->get('page');
            $w = ($item->has('w') && Gp::data()->get('request.ajax','0') == 1) ? $item->get('w') : $item->get('view');
            if ($p != "" && $w != "") {
                $page = $model->getLinkFromPage($p, $w);
            } else {
                $page = false;
            }
            return $page;
            break;
    }
    return false;
}