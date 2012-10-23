<?php

require_once PATH_BASE . '/models/package/Calendar/Template.php';
require_once PATH_LIBS . '/Mindfly/Controller/Action.php';
require_once PATH_BASE . '/models/package/Navigation/Menu/About.php';
require_once PATH_BASE . '/models/package/Navigation/Menu/Header.php';
require_once PATH_BASE . '/models/package/Navigation/Menu/Calendar.php';
require_once SANCTA_PATH . '/User.php';

class SystemController extends Mindfly_Controller_Action {

    //Zend_Navigation
    protected $navigtion;
    // авторизированный пользователь
    protected $user = false;
    protected $flash;

    const LINK_USALY = 0;
    const LINK_AUTORIZED = 1;
    const LINK_NOT_AUTORIZED = 2;

    const IN_ABOUT = 1;
    const IN_HEADER = 2;
    const CALENDAR = 2;

    public function  init() {        
        parent::init();
        // заголовок        
        $this->view->headTitle()->setSeparator(' / ');        
        $this->addCssFile('style.css');

        //$this->addCssFile('layout/layout.css');
        //$this->addCssFile('layout/design.css');
        
        // модальные окна, алерты
        $this->addCssFile('jquery.alerts.css');
        $this->addCssFile('jquery.jgrowl.css');

        //$this->addCssFile('forms.css');
        
        


        $this->addJsFile('jquery-1.4.1.min.js');        
        // подвержения
        $this->addJsFile('jquery.alerts.js');
        // подсказки для форм
        $this->addJsFile('jquery.hint.js');
        // нотификации
        $this->addJsFile('jquery.jgrowl_minimized.js');
        $this->addJsFile('js.js');
        $this->flash = $this->_helper->getHelper('flashMessenger');
              
    }

    protected function getUser() {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector->gotoRoute(array(), 'home');
        }        
        $this->user = Sancta_User::getById(Zend_Auth::getInstance()->getIdentity());
    }

   
    public function preDispatch() {
        parent::preDispatch();
        if ($this->flash->hasMessages()) {
            $this->view->messages = $this->flash->getMessages();
        }
        // если запрос идет с авторизацией
        if ($hash = $this->getRequest()->getParam('hash')) {
            // если пользователь не авторизирован
            if (!Zend_Auth::getInstance()->hasIdentity()) {
                Sancta_User::loginByHash($this->getRequest()->getParam('id'), $hash);
            }
        }        
    }

    protected  function getCategory($categoryId) {
        $categoryTable = new MfCalendarCategoryTable();
        $category = $categoryTable->get($categoryId);
        $this->view->category = $category;
        return $category;
    }


   
  

}