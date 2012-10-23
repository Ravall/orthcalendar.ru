<?php
require_once PATH_LIBS_ZEND . '/Zend/Cache.php';
require_once PATH_LIBS_ZEND . '/Zend/Log.php';
require_once PATH_LIBS_ZEND . '/Zend/Log/Writer/Stream.php';
require_once SANCTA_PATH . '/Abstract/Cache.php';

class Sancta_Abstract_Core extends Sancta_Abstract_Cache{
    private $db;
    
    public function  __construct() {
        $this->db = $db = Zend_Db_Table_Abstract::getDefaultAdapter();
    }

    protected function beginTransaction() {       
       $this->db->beginTransaction();
    }

    protected function commitTransaction() {
        $this->db->commit();
    }

    protected function rollBackTransaction() {
        $this->db->rollBack();
    }
   
    /**
     * получить логгер
     */
    public static function getLogger($logName) {
        $writer = new Zend_Log_Writer_Stream($logName);
        $logger = new Zend_Log($writer);
        $config = new Zend_Config_Ini(PATH_BASE . '/config/config.ini');
        if (!$config->debug->debug) {
            $filter = new Zend_Log_Filter_Priority(Zend_Log::CRIT);
            $logger->addFilter($filter);
        }
        return $logger;
    }    
    
   
  
}