<?php
    require_once realpath(dirname(__FILE__).'/../../../') . '/config/init.php';
    require_once PATH_TESTS . '/init.php';
    require_once SANCTA_PATH . '/Abstract/Cache.php';
    class SanctaTestCase extends Tests_Lib_TestCaseSimple {     
        protected  $db;

        public function  setUp() {
            parent::setUp();
            $this->db = Zend_Db_Table_Abstract::getDefaultAdapter();
            $this->clearCache();
        }

        public function  tearDown() {
            parent::tearDown();
            $this->db->closeConnection();
        }

        private function clearCache() {
            $cache = Sancta_Abstract_Cache::getCahce();
            $cache->clean(Zend_Cache::CLEANING_MODE_ALL);
        }

    }
?>
