<?php
require_once 'SystemController.php';
require_once SANCTA_PATH . '/Event.php';
require_once SANCTA_PATH . '/Article.php';
require_once SANCTA_PATH . '/Peer/Remark.php';
require_once SANCTA_PATH . '/Peer/Event.php';
require_once SYSTEM_PATH . '/model/Form/Remark.php';

require_once PATH_BASE . '/models/package/SmartFunction/SmartFunction.php';

/*
 * Управление ремарками
 */
class RemarkController extends SystemController {
    
    // стили
    protected $_css = array(
        'list'   => array('thickbox.css', 'jquery.alerts.css'),
        'add'   => array('jquery.alerts.css'),
        'edit'   => array('jquery.alerts.css'),
    );
    // скрипты
    protected $_js = array(
        'list' => array('thickbox.js', 'jquery.alerts.js'),
        'add'   => array('jquery.alerts.js'),
        'edit'   => array('jquery.alerts.js'),
    );
    
    public function listAction() {      
        $remarks = Sancta_Peer_Remark::getAll();          
        $this->view->events = $remarks;
    }
    
    public function showAction() {
        $eventId = $this->getRequest()->getParam('event_id');        
        $event = Sancta_Peer_Event::getById($eventId);
        $remarks = Sancta_Peer_Remark::getByEventId($eventId);
        $this->view->event = $event;
        $this->view->remarks = $remarks;
    }


    public function addAction() {
        if (!$eventId = $this->getRequest()->getParam('event_id')) {
            throw new Exception('Для формы требуется event_id');
        }
        $form = new Form_Admin_Remark();
        $form->setEventId($eventId);
        if ($this->getRequest()->isPost()) {
           if ($form->isValid($_POST)) {
               Sancta_Peer_Remark::create(array(
                  'event_id' => $form->getValue('event_id'),
                  'priority' => $form->getValue('priority'),
                  'smart_function' => $form->getValue('smart_function'),
                   Sancta_Text::CONTENT => $form->getValue('description'),
                   Sancta_Text::ANNONCE =>  $form->getValue('annonce'),
               ));
               $this->_redirect('/remark/show/event_id/' . $form->getValue('event_id'));
           }
        }
        $this->view->form = $form;
    }
    
    public function editAction() {
        if (!$id = $this->getRequest()->getParam('id')) {
            throw new Exception('id not find');
        }
        $remark = Sancta_Peer_Remark::getById($id);
        $form = new Form_Admin_Remark();
        $form->setRemarkId($remark);
        if ($this->getRequest()->isPost()) {
           if ($form->isValid($_POST)) {
               $remark->update(array(
                  'event_id' => $form->getValue('event_id'),
                  'priority' => $form->getValue('priority'),
                  'smart_function' => $form->getValue('smart_function'),
                   Sancta_Text::CONTENT => $form->getValue('description'),
                   Sancta_Text::ANNONCE =>  $form->getValue('annonce'),
               ));
               $this->_redirect('/remark/show/event_id/' . $form->getValue('event_id'));
           }
        }
        $this->view->form = $form;
    }
    
    public function deleteAction() {        
        $remark = Sancta_Peer_Remark::getById($this->getRequest()->getParam('id'));
        $remark->setDeleted();
        $this->_redirect('/remark/list');       
    }
}