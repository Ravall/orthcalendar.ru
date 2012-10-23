<?php
/**
 * если в параметрах ресурса в application.ini указать параметр noindex,
 * то этот параметр выставится в мета-теги
 * параметр нужен для seo
 */
class myZend_Controller_Plugin_Noindex extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch($request)
    {
        if ($request->getParam('noindex')) {            
            $view = Zend_Controller_Front::getInstance()->getParam('bootstrap')
                                                        ->getResource('view');
            $view->headMeta()->appendName('robots', 'noindex');
            $view->headMeta()->appendName('robots', 'nofollow');
        }        
    }
}
?>
