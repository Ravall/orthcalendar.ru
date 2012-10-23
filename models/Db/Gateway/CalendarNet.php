<?php
class Db_Gateway_CalendarNet extends Zend_Db_Table_Abstract {
    protected $_name = 'mf_calendar_net';
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
