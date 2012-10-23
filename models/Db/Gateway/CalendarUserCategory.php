<?php
class Db_Gateway_CalendarUserCategory extends Zend_Db_Table_Abstract {
    protected $_name = 'mf_calendar_user_category';
    protected $_primary = 'id';

    // описываем зависимости
    protected $_referenceMap    = array(
        'Object' => array(
            'columns'           => array('user_id'),
            'refTableClass'     => 'Db_Gateway_SystemObject',
            'refColumns'        => array('id')
        ),
    );
}