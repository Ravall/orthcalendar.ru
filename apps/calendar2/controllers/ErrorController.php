<?php
require_once 'SystemController.php';
require_once PATH_LIBS . '/Mindfly/Date.php';
require_once SANCTA_PATH . '/Peer/Mail.php';


class ErrorController extends SystemController
{

    public function setNoIndex()
    {
        $this->view->headMeta()->appendName('robots', 'noindex');
        $this->view->headMeta()->appendName('robots', 'nofollow');
    }

    public function errorAction()
    {
        $this->addTitle('Ошибка | Православный календарь');
        $this->setNoIndex();
        $errors = $this->_getParam('error_handler');

        if ($log = $this->getLog()) {
            $log->crit($this->view->message, $errors->exception);
        }

        $text = Sancta_Peer_Template::getByName('error_mail_to_admin')->getContent(array(
            '%message%' => $errors->exception->getMessage(),
            '%params%' => var_export($errors->request->getParams(), true),
            '%url%' => $this->getRequest()->getBaseUrl() . $this->getRequest()->getRequestUri(),
        ));
        Sancta_Peer_Mail::addMailToAdmin('calendar.sancta error:', $text);

        if (!Config_Interface::isProduction()) {
            switch ($errors->type) {
                case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
                case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
                case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                    // 404 error -- controller or action not found
                    $this->getResponse()->setHttpResponseCode(404);
                    $this->view->message = 'Page not found';
                    break;
                default:
                    // application error
                    $this->getResponse()->setHttpResponseCode(500);
                    $this->view->message = 'Application error';
                    break;
            }
            // conditionally display exceptions
            if ($this->getInvokeArg('displayExceptions') == true) {
                $this->view->exception = $errors->exception;
            }
            } else {
                $this->view->message = 'Страница не найдена или ошибка.';
            }

            $this->view->request   = $errors->request;
    }

    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasPluginResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }


}

