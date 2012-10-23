<?php
class Db_Gateway_SystemObject extends Zend_Db_Table_Abstract {
    protected $_name = 'mf_system_object';
    protected $_primary = 'id';
    // зависимые таблицы
    protected $_dependentTables = array(
        'Db_Gateway_SystemObjectText',                
    );
}