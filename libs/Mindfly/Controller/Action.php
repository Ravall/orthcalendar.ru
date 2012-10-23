<?php

/**
 * дополнение стандартного контроллера
 */
class Mindfly_Controller_Action extends Zend_Controller_Action {

    protected $_pathToCss = '/css/';
    protected $_pathToJs = '/js/';
    
    // стили
    protected $_css = array();
    // скрипты
    protected $_js = array();

    /**
     * подгружаем установленные стили
     */
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
        $this->view->headScript()->appendFile($this->_pathToJs . $fileName);
    }

    /**
     * сокращенная форма подгрузки css файла
     * @param <type> $fileName
     */
    protected function addCssFile($fileName) {
        $this->view->headLink()->appendStylesheet($this->_pathToCss . $fileName);
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
