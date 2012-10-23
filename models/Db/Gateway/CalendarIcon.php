<?php

class Db_Gateway_CalendarIcon extends Zend_Db_Table_Abstract {
    protected $_name = 'mf_calendar_icon';
    protected $_primary = 'id';

    // описываем зависимости
    protected $_referenceMap    = array(
        'Object' => array(
            'columns'           => array('id'),
            'refTableClass'     => 'Db_Gateway_SystemObject',
            'refColumns'        => array('id')
        ),
    );
}
?>