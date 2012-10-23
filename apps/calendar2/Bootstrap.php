<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public function _initLayout() {
        $layout = Zend_Layout::startMvc();        
    }
    
    public function _initRoutes()
    {
        $frontController=Zend_Controller_Front::getInstance();
        $router=$frontController->getRouter();  
        $router->removeDefaultRoutes();
    }
}

