<?php
    require_once dirname(__FILE__) . '/Abstract.php';
    require_once PATH_BASE . '/models/Db/Gateway/SystemCountries.php';

    class Db_Mapper_SystemCountries extends Db_Mapper_Abstract {
        public function  __construct() {
            $this->setDbTable('Db_Gateway_SystemCountries');
        }

        public function getAll() {
            return $this->getDbTable()->fetchAll();
        }

       
    }