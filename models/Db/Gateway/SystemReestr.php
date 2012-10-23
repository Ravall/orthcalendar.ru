<?php
class Db_Gateway_SystemReestr extends Zend_Db_Table_Abstract {
    protected $_name = 'mf_system_registry';
    protected $_primary = 'id';

    // описываем зависимости
    protected $_referenceMap    = array(
        'Object' => array(
            'columns'           => array('objectId'),
            'refTableClass'     => 'Db_Gateway_SystemObject',
            'refColumns'        => array('id')
        ),
    );
}
?>