<?php
class model_sitelinks {
    static $pages;
    function getPageFromLink($page) { 
        if (!is_array(self::$pages)) {
            self::$pages = array();
        }
        if (!array_key_exists($page, self::$pages)) {
            $db = Gp::db();
            self::$pages[$page] = $db->getRow("SELECT * FROM `#__pages` WHERE link = ".$db->quote($page));
            if (is_null(self::$pages[$page])) {
                self::$pages[$page] = array('link'=>$page, 'page'=>$page,'access'=>'{}','status'=>1);
            }
        }
        return  self::$pages[$page];
    }
    function getLinkFromPage($page, $view) { 
         if (!is_array(self::$pages)) {
            self::$pages = array();
        }
        $pageKey = "_".$page."_".$view;
        if (!array_key_exists($pageKey, self::$pages)) {
            $db = Gp::db();
            self::$pages[$pageKey] = $db->getRow("SELECT * FROM `#__pages` WHERE `page` = ".$db->quote($page)." AND `view` = ".$db->quote($view));
            if (is_null(self::$pages[$pageKey])) {
                self::$pages[$pageKey] = array('link'=>$link, 'page'=>$link,'access'=>'{}','status'=>1);
            }
        }
        return  self::$pages[$pageKey];
    }
}