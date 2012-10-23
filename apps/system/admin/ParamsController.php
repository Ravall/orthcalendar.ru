<?php
require_once 'SystemController.php';
require_once SANCTA_PATH . '/Bundle/ReestrObjectParam.php';
require_once SYSTEM_PATH . '/model/Form/Param.php';
/*
 * Управление Параметрами
 */
class ParamsController extends SystemController {
    public function listAction() {
        if (!$objectId = $this->getRequest()->getParam('id')) {        
            throw new Exception('id not find');
        }        
        $params = new Sancta_Bundle_ReestrObjectParam($objectId); 
        $form = new Form_Admin_Param();
        $form->setObjectId($objectId); 
        $this->view->objectId = $objectId;
        $this->view->form = $form;
        $this->view->params = $params->getParams();
    }
    
    public function addAction() {
        $form = new Form_Admin_Param();
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($_POST)) {
                $param = new Sancta_Bundle_ReestrObjectParam($form->getValue('object_id'));
                $param->setParam($form->getValue('key'), $form->getValue('val'));
                $this->_redirect('/params/list/id/' . $form->getValue('object_id'));
            }
        }
    }
    
    public function clearAction() {
        if (!$objectId = $this->getRequest()->getParam('id')) {        
            throw new Exception('id not find');
        }        
        $param = new Sancta_Bundle_ReestrObjectParam($objectId); 
        $param->clear(urldecode($this->getRequest()->getParam('key')));
        $this->_redirect('/params/list/id/' . $objectId);
    }
    
    
}