<?php 
class Sancta_Bundle_StatusesParam {
  
    private $allStatuses = array(
        STATUS_ACTIVE => 1,
        STATUS_PAUSE => 2,
        STATUS_DELETED => 3
    );
    
    private $statuses = array();
    private $numericStatuses = array();


    public function __construct(array $statuses = array(STATUS_ACTIVE)) {        
        if (empty ($statuses)) {
            throw new Exception('array must be not empty');
        }
        $this->statuses = $statuses;        
        foreach ($statuses as $status) {
            $this->numericStatuses[] = $this->allStatuses[$status];
        }
    }
    
    public function getForSql() {        
        return 'in ('.  implode(',', $this->numericStatuses).')';    
    }
    
    public function  getStatusesForCacheIndex() {
        return implode('_', $this->statuses);
    }
    
    public function getArray() {
        return $this->statuses;
    }
}