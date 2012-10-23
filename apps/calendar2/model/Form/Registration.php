<?php
/**
 * форма регистрации
 */
class Form_Calendar_Registration extends Zend_Form
{

    public function  __construct() {
        parent::__construct(
            new Zend_Config_Ini(
                CALENDAR2_PATH . '/config/forms.ini',
                'registration'
            )
        );
    }

    public function init() {
        $this->setDecorators(array(
            array('ViewScript', array('viewScript' => 'forms/registration.phtml'))
        ));
    }

}