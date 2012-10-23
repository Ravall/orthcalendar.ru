<?php
/**
 * форма добавления статьи
 */
class Form_Admin_Remark extends Zend_Form
{
    
    public function  __construct() {
        parent::__construct(new Zend_Config_Ini(SYSTEM_PATH . '/config/forms.ini','remark'));    
    }


    public function setEventId($eventId) {
        $this->setDefaults(array('event_id' => $eventId));
    }
    
    public function setRemarkId($remark) {       
        $this->setDefaults(array(
            'id' => $remark->getId(),
            'event_id' => $remark->getEventId(),
            'priority' => $remark->getPriority(),
            'smart_function' => $remark->getSmartFunction(),
            'description' => $remark->getContent(),
            'annonce' =>  $remark->getAnnonce(),
        ));
        
        $this->getElement('drivePanel')->setAttrib('status', $remark->getStatus());
        $this->getElement('drivePanel')->setAttrib('statusId', $remark->getId());
    }


    public function  init() {
        parent::init();        
        $this->setDecorators(array(
            array('ViewScript', array('viewScript' => 'forms/remark.phtml'))
        ));
    }

   
}