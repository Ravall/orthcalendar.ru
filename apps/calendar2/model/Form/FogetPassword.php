<?php
/**
 * форма восстановления пароля
 */
class Form_Calendar_FogetPassword extends Zend_Form
{

    public function  __construct() {
        parent::__construct(
            new Zend_Config_Ini(
                CALENDAR2_PATH . '/config/forms.ini',
                'foget_password'
            )
        );
    }

    public function init() {
        $this->setDecorators(array(
            array('ViewScript', array('viewScript' => 'forms/foget_password.phtml'))
        ));
    }

}