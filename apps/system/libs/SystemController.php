<?php
require_once 'SystemModel.php';
require_once 'CalendarModel.php';

class SystemController extends Zend_Controller_Action {
    //Zend_Navigation
    protected $navigtion;
    protected $flash;

    public function  init() {
        parent::init();
        // инициируем флеш сообщения
        $this->flash = $this->_helper->getHelper('flashMessenger');
        // заголовок
        $this->addTitle('Mindfly.Admin');
        $this->view->headTitle()->setSeparator(' / ');
        // jquery
        $this->addJsFile('jquery-1.4.2.min.js');
        $this->addCssFile('style.css');

    }

    public function preDispatch() {
        $action = $this->getRequest()->getActionName();        
        if (isset($this->_css[$action])) {
            foreach ($this->_css[$action] as $css) {
                $this->addCssFile($css);
            }
        }
        if (isset($this->_js[$action])) {
            foreach ($this->_js[$action] as $js) {
                $this->addJsFile($js);
            }
        }
    }

    /**
     * сокращенная форма подгружения js файла
     *
     * @param <type> $fileName
     */
    protected function addJsFile($fileName) {
        $this->view->headScript()->appendFile('/js/' . $fileName);
    }

    /**
     * сокращенная форма подгрузки css файла
     * @param <type> $fileName
     */
    protected function addCssFile($fileName) {
        $this->view->headLink()->appendStylesheet('/css/' . $fileName);
    }

    /**
     * сокращенная форма добавления заголовка
     * @param <type> $title
     */
    protected function addTitle($title) {
         $this->view->headTitle($title);
    }


    protected function disableLayout() {
        $this->_helper->layout->disableLayout();
    }

    protected function setLayout($layout) {
        $this->_helper->layout->setLayout($layout);
    }

    
    public function indexAction() {}


}
?>
