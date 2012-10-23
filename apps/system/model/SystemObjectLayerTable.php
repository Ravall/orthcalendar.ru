<?php
/* 
 *  класс для общих действий
 *  над всеми "объектными таблицами"
 */
abstract class SystemObjectLayerTable extends Zend_Db_Table_Abstract {

    /**
     * правила зависимых таблиц
     */
    protected $_referenceMap    = array(
        'Object' => array (
            'columns' => array('id'),
            'refTableClass' => 'MfSystemObjectTable',
            'refColumns' => array('id')
        )
    );

    /**
     * получение row объекта по id
     */
    public function get($id) {
        return $this->fetchRow($this->getAdapter()->quoteInto('id = ?', $id));
    }
}
?>