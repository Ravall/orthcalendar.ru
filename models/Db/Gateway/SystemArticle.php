<?php
/**
 * CREATE TABLE `mf_system_article` (
    `id` int(11) unsigned NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `mf_system_article_ibfk_1` FOREIGN KEY (`id`) REFERENCES `mf_system_object` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8
 */
class Db_Gateway_SystemArticle extends Zend_Db_Table_Abstract {
    protected $_name = 'mf_system_article';
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