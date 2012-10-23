<?php
/**
 * форма авторизации
 */
class Form_Calendar_Authorization extends Zend_Form
{

    public function  __construct() {
        parent::__construct(
            new Zend_Config_Ini(
                CALENDAR2_PATH . '/config/forms.ini',
                'authorization'
            )
        );
    }
    
    public function init() {
        $this->setDecorators(array(
            array('ViewScript', array('viewScript' => 'forms/login.phtml'))
        ));
    }

}