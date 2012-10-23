<?php
require_once PATH_BASE . '/config/env.php';

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public function _initLayout() {
        $layout = Zend_Layout::startMvc();
        $layout->getView()->addHelperPath(PATH_LIBS.'/Mindfly/Outputs','Mindfly_Outputs');
    }

    public function ss_initNavigation() {      
        return $container;
    }

}

