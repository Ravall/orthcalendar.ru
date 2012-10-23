<?php
/**
 * Модель для "текстовый объект"
 * хранит id текста и id объекта к которому 
 * привязывается текст и язык на котором текст сохранен
 *
 * используется как связка для объекта и текста
 *
 * @author ravall
 */
class MfSystemObjectTextTable extends Zend_Db_Table_Abstract {
    protected $_name = 'mf_system_object_text';
    protected $_primary = 'id';
    
    // описываем зависимости
    protected $_referenceMap    = array(
        'Object' => array(
            'columns'           => array('system_object_id'),
            'refTableClass'     => 'MfSystemObjectTable',
            'refColumns'        => array('id')
        ),
        'Text' => array(
            'columns'           => array('system_text_id'),
            'refTableClass'     => 'MfSystemTextTable',
            'refColumns'        => array('id')
        )
    );
}
?>
