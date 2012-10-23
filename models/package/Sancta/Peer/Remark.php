<?php
require_once SANCTA_PATH . '/Abstract/Peer.php';
require_once SANCTA_PATH . '/Bundle/StatusesParam.php';
require_once SANCTA_PATH . '/Bundle/SmartFunction.php';
require_once SANCTA_PATH . '/List/Remark.php';

require_once PATH_BASE . '/models/Db/Mapper/CalendarRemark.php';

/**
 * Класс-контроллер для делегирования получения 
 */
class Sancta_Peer_Remark extends Sancta_Abstract_Peer {
    
    protected static $className = 'Sancta_Remark';
    protected static $classListName = 'Sancta_List_Remark';
    protected static $mapperTable = 'Db_Mapper_CalendarRemark';

    /**
     * тег присутсвующий для всех
     * 
     * @var <type>
     */
    protected static $tag = 'SP_Remark_list';


    static protected $cachedMethods = array(      
        'getAll' => array(
            'index' => 'SP_Remark_getAll_list',
            'tags' => array('SP_Remark_list')
        ),
        'getByEventId' => array(
            'index' => 'SP_Remark_getByEventId_list',
            'tags' => array('SP_Remark_list')
        ),   
        'getRemarksByDay' => array(
            'index' => 'SP_Remark_getRemarksByDay_list',
            'tags' => array('SP_Remark_list', 'smartFunction')
        )
    );


    /**
     * получить все ремарки, привязанные к событию
     * 
     * @param <type> $eventId
     * @return Sancta_List_Remark
     */
    protected static function getByEventId($eventId) {        
        $remarkTable = self::getMapperTable();        
        $remarkList = new Sancta_List_Remark($remarkTable->getRemarkIdByEventId($eventId), 'id');
        return $remarkList;
    }
    
    public static function create($params) {        
        $params['function_id'] = Sancta_Bundle_SmartFunction::create($params['smart_function']);                
        return parent::create($params);
    }
    
    /**
     * получить ремарки за сегодня
     * 
     * @param type $day
     * @return listClass 
     */
    protected static function getRemarksByDay($day) {        
        $netTable = new Db_Mapper_CalendarNet();
        $remarkIds = $netTable->getRemarksByDay($day->getY(), $day->getM(), $day->getD());        
        $listClass = self::getClassListName();        
        $list = new $listClass($remarkIds);        
        return $list;
    }
}