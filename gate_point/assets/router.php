<?php
/**
 * Come vengono scritti i link con l'htaccess attivo
 */
function routerBuild($query, $routerClass) {
    $load = Gp::load();
    if (GpRegistry::getInstance()->get('config.htaccess', true)) {
        $parse = $routerClass->parseUrl($query, false);     
        if (is_array($parse) && array_key_exists('query', $parse) && array_key_exists('page', $parse['query'])) {
            $pageInfo = Gp::action()->invoke('routerBuild', $parse['query']['page']);
            if ($pageInfo != $parse['query']['page'] && isset($pageInfo['link'])) { 
                $parse['query']['page'] = $pageInfo['link'];
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
        //$pageInfo = $load->module('sitelinks','getPageFromLink', array('parsePage'=>$parseUrl['query']['page']));
        $pageInfo = Gp::action()->invoke('routerParse', $parseUrl['query']['page']);
        if ($pageInfo != $parseUrl['query']['page'] && isset($pageInfo['link'])) { 
            $parseUrl['query']['page'] = $pageInfo['link'];
            $parseUrl['info'] = $pageInfo;
        } else {
            $parseUrl['info'] = array('filename'=>$parseUrl['query']['page']);
        }
    }
    return $parseUrl;
}