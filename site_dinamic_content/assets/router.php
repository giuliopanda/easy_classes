<?php
/**
 * Come vengono scritti i link con l'htaccess attivo
 */
function routerBuild($query, $routerClass) {
    $load = Gp::load();
    if (GpRegistry::getInstance()->get('config.htaccess', true)) {
        $parse = $routerClass->parseUrl($query, false);
        if (is_array($parse) && array_key_exists('query', $parse) && array_key_exists('page', $parse['query'])  && array_key_exists('view', $parse['query'])) {
            $pageInfo = $load->module('sitelinks','getLinkFromPage', array('parsePage'=>$parse['query']['page'], 'parseView'=>$parse['query']['view']));
            if ($pageInfo != false) { 
                $parse['query']['page'] = $pageInfo['link'];
                unset($parse['query']['view']);
                $parse['info'] = $pageInfo;
            } 
        }
        
        $query = $routerClass->queryToPath($parse, "page", "id");

    }
    return $query;
}
/**
 * I dati ricavati da una url.
 *  Se servono filtri particolari li posso mettere qui dentro
 */
function routerParse($parseUrl, $routerClass) {
    $load = Gp::load();
    $parseUrl = $routerClass->pathToQuery($parseUrl, "page", "id");
    if (is_array($parseUrl) && array_key_exists('query', $parseUrl) && array_key_exists('page', $parseUrl['query'])) {
        $pageInfo = $load->module('sitelinks','getPageFromLink', array('parsePage'=>$parseUrl['query']['page']));
        if ($pageInfo != false) { 
            $parseUrl['query']['page'] = $pageInfo['page'];
            $parseUrl['info'] = $pageInfo;
        } 
    }
    return $parseUrl;
}