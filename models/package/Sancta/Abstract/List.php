<?php
class Sancta_Abstract_List implements IteratorAggregate {
    protected $item = array();
    protected $ids = array();
    protected $className;


    public function getIterator() {
        return new ArrayIterator($this->item);
    }
    
    /**
     * получить количество элементов в списке
     * @return type 
     */
    public function getCount() {
        return count($this->item);
    }

    
    /**
     * $export - массив из которых следует брать объекты, 
     * если он не указан - объекты создаются
     * 
     * @param type $arrayOfIds
     * @param type $columnName
     * @param type $export 
     */
    public function __construct($arrayOfIds, $columnName = null, $export = array()) {
        $array =  $columnName ? $this->getColumnFromArray($arrayOfIds, $columnName) : $arrayOfIds;        
        $this->validateArray($array);
        
        if (empty ($export)) {
            $this->ids = $array;
            $this->createItems($array);
        } else {
            $this->export($export, $arrayOfIds);
        }
    }

    private function getColumnFromArray($array, $columnName) {
        $result = array();
        foreach ($array as $item) {
            if (!isset ($item[$columnName])) {
                throw new Exception('column ' . $columnName. ' must be exists');
            }
            $result[] = $item[$columnName];
        }
        return $result;
    }


    private function validateArray(array $array) {                 
        foreach ($array as $key => $id) {            
            if (!is_numeric($key) || !is_numeric($id)) {                
                throw new Exception(
                    'Входящий массив должен быть одномерным массивом идентификаторов'
                );
            }
        }
    }
    
    /**
     * заполняем массив $item объектами моделей
     * 
     * @param type $ids 
     */
    private function createItems($ids) {        
        $className = $this->classNamePeer;        
        foreach ($ids as  $id) {                        
            $this->item[] = $className::getById($id);            
        }
    }
    
    private function export($items, $ids) {        
        foreach ($items as $item) {
            if (in_array($item->getId(), $ids)) {
                $this->ids[] = $item->getId();
                $this->item[] = $item;
            }
        }
    }


    public function getIds() {
        return $this->ids;
    }
    
   

    /**
     *  ?     
     */
    public function statusFilter($statuses = null) {
        $statuses = $this->_prepareSanctaBundleStatusesParam($statuses);        
        $ids = array();        
        foreach ($this->item as $item) {
            if (in_array($item->getStatus(),$statuses->getArray())) {                
                $ids[] = $item->getId();
            }
        }
        return $this->createFiltered($ids);
    }
    
     /**
      *  ?
      * проверяем пареметр статусы 
      * 
      * @param Sancta_Bundle_StatusesParam $statuses
      * @return Sancta_Bundle_StatusesParam 
      */
    protected static function _prepareSanctaBundleStatusesParam($statuses) {
        if (!$statuses) {
            $statuses = new Sancta_Bundle_StatusesParam();
        }        
        if (!is_a($statuses, 'Sancta_Bundle_StatusesParam')) {
            throw new Exception('$statuses must be instance Sancta_Bundle_StatusesParam');
        }
        return $statuses;
    }
    
    
    protected function filter($conditionFunction, $result = true, $params = array()) {
        $ids = array();
        foreach ($this->item as $item) {                 
            if ($item->{$conditionFunction}($params) == $result) {
                $ids[] = $item->getId();
            }
        }
        return $this->createFiltered($ids);        
    }


    protected function createFiltered($ids) {
        $listClass = $this->listClass;
        return new $listClass($ids, null, $this->item);
    }
    
}
?>