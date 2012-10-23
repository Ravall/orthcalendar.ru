<?php
/**
 * CREATE TABLE `mf_system_mail_stack` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `subject` varchar(255) DEFAULT NULL,
    `to` varchar(255) DEFAULT NULL,
    `text` text,
    `is_send` tinyint(1) DEFAULT '0',
   PRIMARY KEY (`id`),
   KEY `NewIndex1` (`is_send`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8

 */
class Db_Gateway_SystemMailStack extends Zend_Db_Table_Abstract {
    protected $_name = 'mf_system_mail_stack';
    protected $_primary = 'id';
}
?>