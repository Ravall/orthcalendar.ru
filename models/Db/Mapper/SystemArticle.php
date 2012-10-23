<?php
    require_once dirname(__FILE__) . '/Abstract.php';
    require_once PATH_BASE . '/models/Db/Gateway/SystemArticle.php';
    
    class Db_Mapper_SystemArticle extends Db_Mapper_Abstract {
        public function  __construct() {
            $this->setDbTable('Db_Gateway_SystemArticle');
        }

        
        public function create($params) {
            $row = $this->getDbTable()->createRow();
            $row->id = $params['object_id'];     
            $row->save();
            return $row;
        }
        
        public function getAll() {            
            $sql = 'SELECT article.id FROM mf_system_article article';                    
            return $this->getDbTable()->getAdapter()->fetchAll($sql);
        }
        

    }