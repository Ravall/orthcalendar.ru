<?php
class Admin_Element_DrivePanel extends Zend_Form_Element
{
    public function  init() {
        parent::init();
        $this->addDecorator('ViewScript', array(
            'viewScript' => 'forms/element/drive-panel.phtml',
            'status' => $this->getAttrib('status') ? $this->getAttrib('status') : STATUS_ACTIVE,
            'statusId' => $this->getAttrib('statusId'),
            'buttonDeleteUrl' => $this->getAttrib('buttonDeleteUrl'),
            'buttonCancellUrl' => $this->getAttrib('buttonCancellUrl'),
        ));
        
    }

   public function render(Zend_View_Interface $view = null) {
       $this->addDecorator('ViewScript', array(
            'viewScript' => 'forms/element/drive-panel.phtml',
            'status' => $this->getAttrib('status') ? $this->getAttrib('status') : STATUS_ACTIVE,
            'statusId' => $this->getAttrib('statusId'),
            'buttonDeleteUrl' => $this->getAttrib('buttonDeleteUrl'),
            'buttonCancellUrl' => $this->getAttrib('buttonCancellUrl'),
        ));
       
       
        
       return parent::render($view);
    }
}