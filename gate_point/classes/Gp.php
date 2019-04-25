<?php
$currentDir = dirname(__FILE__);
require_once($currentDir.'/GpLog.php');
require_once($currentDir.'/GpRegistry.php');
require_once($currentDir.'/GpListener.php');
require_once($currentDir.'/GpDBMySql.php');
require_once($currentDir.'/GpRouter.php');
require_once($currentDir.'/GpLoad.php');

/** Facedes */
class Gp
{
    /**
     * GET GpRegistry Instance
     */
    static function data() {
        return GpRegistry::getInstance();          
    }
    /**
     * GET GpListener Instance
     */
    static function action() {
        return GpListener::getInstance();
    }
    /**
     * GET GpDBMySql Instance
     */
    static function db() {
        return GpDBMySql::getInstance();
    }
    /**
     * GET GpRouter Instance
     */
    static function route() {
        return GpRouter::getInstance();
    }
    /**
     * GET GpLoad Instance
     */
     static function load() {
        return GpLoad::getInstance();
    }
    /**
     * GET GpLoad Instance
     */
     static function log() {
        return GpLog::getInstance();
    }
}
