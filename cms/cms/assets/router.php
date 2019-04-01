<?php
/**
 * Come vengono scritti i link con l'htaccess attivo
 */
function routerBuild($query, $routerClass) {
    if (GPRegistry::getInstance()->get('config.htaccess', true)) {
        $parse = $routerClass->parseUrl($query, false);
        $query = $routerClass->queryToPath($parse, "page");

    }
    return $query;
}
/**
 * I dati ricavati da una url.
 *  Se servono filtri particolari li posso mettere qui dentro
 */
function routerParse($parseUrl, $routerClass) {

    $parseUrl = $routerClass->pathToQuery($parseUrl, "page");
    return $parseUrl;
}