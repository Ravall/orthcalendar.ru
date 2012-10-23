<?php
    require_once dirname(__FILE__) . '/Abstract.php';
    require_once PATH_BASE . '/models/Db/Gateway/SmartFunction.php';

    class Db_Mapper_CalendarSmartFunction extends Db_Mapper_Abstract {

        public function  __construct() {
            $this->setDbTable('Db_Gateway_SmartFunction');
        }
        
        public function create($function) {
            $row = $this->getDbTable()->createRow();            
            $row->smart_function = $function;
            $row->save();
            return $row->id;
        }
        
        /**
         * получаем пачку перезагруженных функций
         * 
         * @param type $limit
         * @return type 
         */
        public function getReloaded($limit) {
            $sql =  'SELECT * FROM mf_calendar_smart_function'                  
                  . ' WHERE  reload = 1 LIMIT ' . $limit;                  
            return $this->getDbTable()->getAdapter()->fetchAll($sql);
        }
        
        public function setReloaded($functionId) {
            $functionId = (int) $functionId;
            $sql =  'UPDATE mf_calendar_smart_function'                  
                  . ' SET reload = 0 WHERE id = ' . $functionId;                  
            return $this->getDbTable()->getAdapter()->query($sql);
        }
    }