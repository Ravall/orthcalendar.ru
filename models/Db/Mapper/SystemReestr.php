<?php
    require_once dirname(__FILE__) . '/Abstract.php';
    require_once PATH_BASE . '/models/Db/Gateway/SystemReestr.php';

    class Db_Mapper_SystemReestr extends Db_Mapper_Abstract {
        public function  __construct() {
            $this->setDbTable('Db_Gateway_SystemReestr');
        }
        
        public function getParam($objectId, $key) {
            $objectId = (int) $objectId;
            $sql = 'SELECT `value` FROM mf_system_registry'
                 . ' where objectId = ' . $objectId
                 . ' and `key` = ' . $this->quote($key);
            $result = $this->getDbTable()->getAdapter()->fetchRow($sql);
            return isset($result['value']) ? $result['value'] : false;
        }

        public function setParam($objectId, $key, $value) {
            $objectId = (int) $objectId;
            $value = $this->quote($value);
            $key = $this->quote($key);

            $sql = 'INSERT INTO mf_system_registry '
                 . ' (objectId, `key`, `value`) VALUE'
                 . ' (' . $objectId . ', ' . $key . ','
                 . ' ' . $value . ') ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)';
            
            $this->getDbTable()->getAdapter()->query($sql);
        }

        public function getParams($objectId) {
            $objectId = (int) $objectId;
            $sql = 'SELECT * FROM mf_system_registry'
                 . ' WHERE objectId = ' . $objectId;
            return $this->getDbTable()->getAdapter()->fetchAll($sql);
        }
        
        public function clear($objectId, $key) {
            $sql = 'delete from mf_system_registry'
                . ' WHERE objectId = ' . $objectId
                . ' AND `key` = ' . $this->quote($key);            
            $this->getDbTable()->getAdapter()->query($sql);
        }
    }
