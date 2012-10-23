<?php
abstract class Db_Mapper_Abstract {
    protected $_dbTable;
   
    
    public function beginTransaction() {         
       $this->getDbTable()->getAdapter()->beginTransaction();
    }

    public function commitTransaction() {
        $this->getDbTable()->getAdapter()->commit();
    }

    public function rollBackTransaction() {
        $this->getDbTable()->getAdapter()->rollBack();
    }
    
    public function setDbTable($dbTable)
    {
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        
        if (is_string($dbTable)) {
            $dbTable = new $dbTable(array('db' => $db));        
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }
    
    public function getDbTable() {
        return $this->_dbTable;
    }

    public function quote($value) {
        return $this->getDbTable()->getAdapter()->quote($value);
    }
    
    /**
     * получаем по id запись
     * 
     * @param type $id
     * @return type 
     */
    public function getById($id) {
        return $this->getDbTable()->find($id)->current();
    }

    /**
     * обновления записи в таблице, по указанному ключу
     * 
     * @param type $id
     * @param type $params
     * @param type $keys
     * @return type 
     */
    public function update($id, $params, $keys) {        
        $where = $this->getDbTable()->getAdapter()->quoteInto('id = ?', $id);
        $updateArray = $this->getInterestsParams($params, $keys);
        if (!empty($updateArray)) {
            $this->getDbTable()->update($updateArray, $where);
        }
        return $this->getById($id);
    }
    
    /**
     * выбираем из массива всех параметров только параметры по интересующим нас ключам
     * @param type $params
     * @param type $interestsKeys
     * @return type 
     */
    private function getInterestsParams($params, $interestsKeys) {
        $interestsParams = array();
        foreach ($params as $key => $value) {
            if (in_array($key, $interestsKeys)) {
                $interestsParams[$key] = $value;
            }
        }
        return $interestsParams;
    }
   
}
