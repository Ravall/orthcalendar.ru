<?php
require_once PATH_BASE . '/models/Db/Mapper/SystemReestr.php';
/**
 * Класс параметры объекта
 *
 * @author ravall
 */
class Sancta_Bundle_ReestrObjectParam {

    private $objectId;

    public function __construct($objectId) {
        $this->objectId = $objectId;
    }


    public function getParam($key) {
        $reestrTable = new Db_Mapper_SystemReestr();
        return $reestrTable->getParam($this->objectId, $key);
  }

    public function setParam($key, $val) {
        $reestrTable = new Db_Mapper_SystemReestr();
        $reestrTable->setParam($this->objectId, $key, $val);
    }

    public function getParams() {
        $reestrTable = new Db_Mapper_SystemReestr();
        $params = array();
        foreach ($reestrTable->getParams($this->objectId) as $row) {
            $params[$row['key']] = $row['value'];
        }
        return $params;        
    }
    
    public function clear($key) {
        $reestrTable = new Db_Mapper_SystemReestr();
        $reestrTable->clear($this->objectId, $key);
    }
}