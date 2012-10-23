<?php
/**
 * форма добавления параметров к объектам
 */
class Form_Admin_Param extends Zend_Form
{
    
    public function  __construct() {
        parent::__construct(new Zend_Config_Ini(SYSTEM_PATH . '/config/forms.ini', 'param'));    
    }
    
    public function setObjectId($id) {
        $this->setDefaults(array('object_id' => $id));
    }

    public function  init() {
        parent::init();        
        $this->setDecorators(array(
            array('ViewScript', array('viewScript' => 'forms/param.phtml'))
        ));
    }

   
}