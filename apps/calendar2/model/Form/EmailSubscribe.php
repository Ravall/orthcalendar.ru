<?php
/**
 * форма восстановления пароля
 */
class Form_Calendar_EmailSubscribe extends Zend_Form
{

    public function  __construct() {
        parent::__construct(
            new Zend_Config_Ini(
                CALENDAR2_PATH . '/config/forms.ini',
                'email_subscribe'
            )
        );
    }

    public function init() {
        $this->setDecorators(array(
            array('ViewScript', array('viewScript' => 'forms/email_subscribe.phtml'))
        ));
    }

}