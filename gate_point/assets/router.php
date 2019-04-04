<?php
/**
 * Come vengono scritti i link con l'htaccess attivo
 */
function routerBuild($query, $routerClass) {
    $load = Gp::load();
    if (GpRegistry::getInstance()->get('config.htaccess', true)) {
        $parse = $routerClass->parseUrl($query, false);        
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
    return $parseUrl;
}