<?php
/**
 * форма добавления статьи
 */
class Form_Admin_Filter extends Zend_Form
{
    public function  __construct($action) {
        parent::__construct(new Zend_Config_Ini(SYSTEM_PATH . '/config/forms.ini','filter'));
        $this->setAction($action);
    }

    public function setFilter($filterArray) {
        $this->setDefaults(array(
           'statusActive' => (int) in_array(STATUS_ACTIVE, $filterArray),
           'statusPause'  => (int) in_array(STATUS_PAUSE, $filterArray),
           'statusDelete' => (int) in_array(STATUS_DELETED, $filterArray),
        ));
    }

    /**
     * получаем массив фильтров
     * @return string
     */
    public function getFilter() {
        $array = array();
        if ($this->getValue('statusActive')) {
            $array[] = STATUS_ACTIVE;
        }
        if ($this->getValue('statusPause')) {
            $array[] = STATUS_PAUSE;
        }
        if ($this->getValue('statusDelete')) {
            $array[] = STATUS_DELETED;
        }
        return $array;
    }


    public function  init() {
        parent::init();
        $this->setDecorators(array(
            array('ViewScript', array('viewScript' => 'forms/filter.phtml'))
        ));
    }

}