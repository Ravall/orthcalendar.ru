<?php
    require_once dirname(__FILE__) . '/Abstract.php';
    require_once PATH_BASE . '/models/Db/Gateway/SystemTemplate.php';

    class Db_Mapper_SystemTemplate extends Db_Mapper_Abstract {
        public function  __construct() {
             $this->setDbTable('Db_Gateway_SystemTemplate');
        }

        public function create($params) {
            $row = $this->getDbTable()->createRow();
            $row->id = $params['object_id'];     
            $row->save();
            return $row;
        }
        
        public function getAll() {
            $sql = 'SELECT template.id FROM mf_system_template template                    
                    JOIN mf_system_object object ON object.id = template.id';                    
            return $this->getDbTable()->getAdapter()->fetchAll($sql);
        }
    }
