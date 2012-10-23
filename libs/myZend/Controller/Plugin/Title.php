<?php
/**
 * если в параметрах ресурса в application.ini указать параметр noindex,
 * то этот параметр выставится в мета-теги
 * параметр нужен для seo
 */
class myZend_Controller_Plugin_Title extends Zend_Controller_Plugin_Abstract
{
    public function predispatch($request)
    {
        if ($title = $request->getParam('title')) {                   
            $view = Zend_Controller_Front::getInstance()->getParam('bootstrap')
                                                        ->getResource('view');            
            $view->headTitle($title);
            $view->headTitle('Православный календарь');
        }        
    }
}
?>
