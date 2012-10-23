<?php
require_once 'SystemController.php';
class IndexController extends SystemController
{

    public function indexAction() {
        $this->_helper->redirector->gotoRoute(array(), 'orthodoxy');
    }

    public function topMenuAction() {
        $this->view->hasIdentity = Zend_Auth::getInstance()->hasIdentity();
    }
  
    public function pageAction() {
        $template = $this->getRequest()->getParam('template');        
        $this->view->template = $template;    
        $this->render($template);
    }

    


}

