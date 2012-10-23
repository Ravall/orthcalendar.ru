<?php
require_once 'SystemController.php';
require_once CALENDAR2_PATH . '/model/Form/Reflection.php';
require_once SANCTA_PATH . '/Peer/Mail.php';

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
    
    
    private function _getRouteParam($route) 
    {
        switch ($route) {
            case 'oldarticle':
                $routeParam = array(
                    'event_id' => $this->getRequest()->getParam('event_id'),
                    'id' => $this->getRequest()->getParam('article_id')
                );
                break;
            case 'eventorthodoxy':
            case 'eventorthodoxytemp':
                $routeParam = array(
                    'id' => $this->getRequest()->getParam('id')
                );
                break;
            default:
                $routeParam = array();
                break;
        }
        return $routeParam;
    }

    /**
     * перелинковка, сохранение
     * 
     * @return type 
     */
    public function redirectAction()
    {
        $route = $this->getRequest()->getParam('route');
        $oldRouter = $this->getRequest()->getParam('oldRouter');
        $router = Zend_Controller_Front::getInstance()->getRouter();
        $url = $router->assemble($this->_getRouteParam($oldRouter), $route);
        return $this->_redirect($url, array('code' => 301));
    }


    /**
     * форма обратной связи
     */
    public function contactAction()
    {
        $reflectionForm = new Form_Calendar_Reflection();        
        if ($this->getRequest()->isPost() && $reflectionForm->isValid($_POST)) {
            $text = Sancta_Peer_Template::getByName('error_mail_to_admin')->getContent(array(
                '%message%' => $reflectionForm->getValue('text'),
                '%params%' => 'email: ' . $reflectionForm->getValue('email'),
                '%url%' => '',
            ));
            Sancta_Peer_Mail::addMailToAdmin('FORM CONTACT:', $text);
            $this->view->successText = Config_Interface::get('reflection_success', 'flash_text');               
        }
         /**
         * view
         */
        $this->view->form = $reflectionForm;
    }


}

