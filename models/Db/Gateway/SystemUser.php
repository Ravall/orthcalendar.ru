<?php
class Db_Gateway_SystemUser extends Zend_Db_Table_Abstract {
    protected $_name = 'mf_system_user';
    protected $_primary = 'id';
    /**
     * правила зависимых таблиц
     */
    protected $_referenceMap    = array(
        'Object' => array (
            'columns' => array('id'),
            'refTableClass' => 'Db_Gateway_SystemObject',
            'refColumns' => array('id')
        )
    );
}
?>