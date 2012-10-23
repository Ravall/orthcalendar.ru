<?php
require_once SANCTA_PATH . '/Event.php';
require_once SANCTA_PATH .'/Abstract/Peer.php';
require_once SANCTA_PATH . '/Bundle/StatusesParam.php';
require_once SANCTA_PATH . '/Bundle/SmartFunction.php';
require_once SANCTA_PATH . '/List/Event.php';
require_once PATH_BASE . '/models/Db/Mapper/CalendarRemark.php';

/**
 * Класс-контроллер для делегирования получения
 */
class Sancta_Peer_Event extends Sancta_Abstract_Peer {
    
     protected static $className = 'Sancta_Event';     
     protected static $classListName = 'Sancta_List_Event';
     protected static $mapperTable = 'Db_Mapper_CalendarEvent';
    
     protected static $tag = 'SP_Event_list';
     
     const PERIODIC = 1;
     const NOT_PERIODIC = 0;
     
     static protected $cachedMethods = array(      
        'getAll' => array(
            'index' => 'SP_Event_getAll_list',
            'tags' => array('SP_Event_list')
        ),
        'getByCategoryId' => array (
            'index' => 'SP_Event_getByCategoryId_list',
            'tags' => array('SP_Event_list')
        ),
        'getEventListInDay' => array(
            'index' => 'SP_Event_getEventListInDay_list',
            'tags' => array('SP_Event_list', 'smartFunction')
        ),         
        'getEventListInYear' => array(
            'index' => 'SP_Event_getEventListInYear_list',
            'tags' => array('SP_Event_list', 'smartFunction')
        ),         
        'getNextEvents' => array(
            'index' => 'SP_Event_getNextEvents_list',
            'tags' => array('SP_Event_list', 'smartFunction')
        ),
        'getPrevEvents' => array(
            'index' => 'SP_Event_getPrevEvents_list',
            'tags' => array('SP_Event_list', 'smartFunction')
        ),
        'getByArticleId' => array(
            'index' => 'SP_Event_getByArticleId_list',
            'tags' => array('SP_Event_list')            
        )
    );
     
     /**
      * получение событий в категории
      * 
      * @test Sancta_Peer_EventTest::testGetByCategoryId
      * @param type $categoryId
      * @return listClass 
      */
     protected static function getByCategoryId($categoryId) {         
           $systemRelationTable = new Db_Mapper_SystemRelation();           
           $listClass = self::getClassListName();
           return new $listClass($systemRelationTable->getRelatedObjectsByParentId($categoryId), 'id');           
     }
     
     protected static function getByArticleId($acticleId) {
         $acticle = Sancta_Peer_Article::getById($acticleId);
         $listClass = self::getClassListName();
         return new $listClass($acticle->getRelatedEvents());
         
     }

     /**
      * создание события
      * 
      * @test Sancta_Peer_EventTest::testCreate
      * @param array $params
      * @return type 
      */
     public static function create($params = array()) {         
        $function = isset($params['smart_function']) ? $params['smart_function'] : '';
        $params['function_id'] = Sancta_Bundle_SmartFunction::create($function);                        
        return parent::create($params);
    }
    
    
   
    
    /**
     * получить список событий за день
     * по указанной категории
     * 
     * @param type $categoryId
     * @param type $year
     * @param type $month
     * @param type $day
     * @return listClass 
     */
    protected static  function getEventListInDay($categoryId, $date, $periodic = false) {          
        $net = new Db_Mapper_CalendarNet();
        $listClass = self::getClassListName();
        $ids = $net->getEventListInDay($categoryId, $date->getY(), $date->getM(), $date->getD(), $periodic);                
        return new $listClass($ids);
    }
    
    /**
     * получаем события которые будут в заданном году
     * 
     * @param type $categoryId
     * @param type $date
     * @return listClass 
     */
    protected static function getEventListInYear($categoryId, $date) {
        $net = new Db_Mapper_CalendarNet();
        $listClass = self::getClassListName();
        $ids = $net->getEventListInYear($categoryId, $date->getY());                
        return new $listClass($ids);
    }

    
    /**
     * получить список следующих событий 
     * 
     * @param type $categoryId
     * @param type $date
     * @return listClass 
     */
    protected static function getNextEvents($categoryId, $date) {        
        $net = new Db_Mapper_CalendarNet();        
        $minimumNextDays = Config_Interface::get('minimumNextDays', 'application');
        $countNextLimit = Config_Interface::get('countNextLimit', 'application');
        $events = $net->getEventsNextNDays($categoryId, $minimumNextDays, $date->getDay());
        if (count($events) < $countNextLimit) {
            $events = $net->getEventsIdNextLimit($categoryId, $countNextLimit, $date->getDay());
        }
        $listClass = self::getClassListName();
        return new $listClass($events);
    }
    
    
    
    protected static function getPrevEvents($categoryId, $date) {        
        $net = new Db_Mapper_CalendarNet();
        $minimumPrevDays =  Config_Interface::get('minimumPrevDays', 'application');
        $countPrevLimit = Config_Interface::get('countPrevLimit', 'application');
        $events = $net->getEventsPrevNDays($categoryId, $minimumPrevDays, $date->getDay());        
        if (count($events) < $countPrevLimit) {
            $events = $net->getEventsPrevNLimit($categoryId, $countPrevLimit, $date->getDay());
        }
        $listClass = self::getClassListName();
        return new $listClass($events);
    }



   
  
    
    
    
    
}