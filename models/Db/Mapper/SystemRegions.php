<?php
    require_once dirname(__FILE__) . '/Abstract.php';
    require_once PATH_BASE . '/models/Db/Gateway/SystemRegions.php';

    class Db_Mapper_SystemRegions extends Db_Mapper_Abstract {
        public function  __construct() {
            $this->setDbTable('Db_Gateway_SystemRegions');
        }

        public function getRegionsByCountry($countryId) {
            return $this->getDbTable()->fetchAll($this->getDbTable()->getAdapter()->quoteInto('country_id = ?', $countryId));
        }


    }