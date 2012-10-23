<?php
/**
 * форма обратной связи
 */
class Form_Calendar_Reflection extends Zend_Form
{

    public function  __construct() {
        parent::__construct(
            new Zend_Config_Ini(
                CALENDAR2_PATH . '/config/forms.ini',
                'reflection'
            )
        );
    }

    public function init() {
        $this->setDecorators(array(
            array('ViewScript', array('viewScript' => 'forms/reflection.phtml'))
        ));
    }

}