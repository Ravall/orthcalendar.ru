<?php
    require_once dirname(__FILE__) . '/Abstract.php';
    require_once PATH_BASE . '/models/Db/Gateway/SystemCities.php';

    class Db_Mapper_SystemCities extends Db_Mapper_Abstract {
        public function  __construct() {
            $this->setDbTable('Db_Gateway_SystemCities');
        }

        public function getCitiesByRegion($countryId) {
            return $this->getDbTable()->fetchAll($this->getDbTable()->getAdapter()->quoteInto('region_id = ?', $countryId));
        }


    }