<?php
/**
CREATE TABLE `mf_system_relation` (
  `id` INT(11) UNSIGNED DEFAULT NULL,
  `parent_id` INT(11) UNSIGNED DEFAULT NULL,
  UNIQUE KEY `NewIndex1` (`id`,`parent_id`),
  KEY `FK_1mf_system_relation` (`parent_id`),
  KEY `NewIndex2` (`id`),
  CONSTRAINT `FK_1mf_system_relation` FOREIGN KEY (`parent_id`) REFERENCES `mf_system_object` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_mf_system_relation` FOREIGN KEY (`id`) REFERENCES `mf_system_object` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8

 */
class Db_Gateway_SystemRelation extends Zend_Db_Table_Abstract {
    protected $_name = 'mf_system_relation';
    protected $_primary = 'id';

    // описываем зависимости
    protected $_referenceMap    = array(
        'Object' => array(
            'columns'           => array('id'),
            'refTableClass'     => 'Db_Gateway_SystemObject',
            'refColumns'        => array('id')
        ),
        'RelateParentObject' => array(
            'columns'           => array('parent_id'),
            'refTableClass'     => 'Db_Gateway_SystemObject',
            'refColumns'        => array('id')
        ),
    );
}
?>