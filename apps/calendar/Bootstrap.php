<?php
require_once PATH_BASE . '/config/db.adapter.php';
require_once PATH_BASE . '/config/env.php';

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public function _initLayout() {
        $layout = Zend_Layout::startMvc();        
    }

    /**
     * инициализация маршрутов
     */
    public function _initRoute() {
        $config = new Zend_Config_Ini(CALENDAR_PATH.'/config/route.ini');
        $router = new Zend_Controller_Router_Rewrite();
        $router->addConfig($config);
        Zend_Controller_Front::getInstance()->setRouter($router);
    }


}

