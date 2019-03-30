<?php
/**
 * Come vengono scritti i link con l'htaccess attivo
 */
function routerBuild($query, $routerClass) {
    if (GPRegistry::getInstance()->get('config.htaccess', true)) {
        $parse = $routerClass->parseUrl($query, false);
        // trasforma il path in query dove il primo path è page e il secondo è view
        $query = $routerClass->queryToPath($parse, "page", "view");
        /*
        if (array_key_exists('query',$parse)) {
            $p = $parse['query'];
            if (array_key_exists('page',$p) && array_key_exists('view', $p))  {
                $newAlias = $p['page'] ."/". $p['view'];
                unset($parse['query']['page']);
                unset($parse['query']['view']);
            } else if (array_key_exists('page', $p) && !array_key_exists('view', $p))  { 
                $newAlias = $p['page'];
                unset($parse['query']['page']);
            }
            return "/".$newAlias.$routerClass->implodeQuery($parse['query']);
        }
        */
    }
    return $query;
}
/**
 * I dati ricavati da una url.
 *  Se servono filtri particolari li posso mettere qui dentro
 */
function routerParse($parseUrl, $routerClass) {
    // trasforma il path in query dove il primo path è page e il secondo è view
    $parseUrl = $routerClass->pathToQuery($parseUrl, "page", "view");
    /*
    
    if (array_key_exists('query',$parseUrl)) {
    } else {
       $parseUrl['query'] = array();
    }
    if (count($parseUrl['path']) > 0) {
        if (!array_key_exists('page', $parseUrl['query'])) {
            $parseUrl['query']['page'] = array_shift($parseUrl['path']);
        } else {
            array_shift($parseUrl['path']);
        }
    }
    if (count($parseUrl['path']) > 0) {
        if (!array_key_exists('view', $parseUrl['query'])) {
            $parseUrl['query']['view'] = array_shift($parseUrl['path']);
        } else {
            array_shift($parseUrl['path']);
        }
    }
    if (count ($parseUrl['path']) == 0) {
        unset($parseUrl['path']);
    }
    
    */
    return $parseUrl;
}