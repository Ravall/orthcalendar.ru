<?php
require_once SANCTA_PATH . '/Icon.php';
require_once SANCTA_PATH . '/Abstract/Peer.php';
require_once SANCTA_PATH . '/List/Icon.php';
require_once PATH_BASE . '/models/Db/Mapper/CalendarIcon.php';
require_once SANCTA_PATH . '/Abstract/Peer.php';
require_once SANCTA_PATH . '/Bundle/StatusesParam.php';

/**
 * Класс-контроллер для делегирования получения статей
 */
class Sancta_Peer_Icon extends Sancta_Abstract_Peer 
{
	protected static $className = 'Sancta_Icon';
    protected static $classListName = 'Sancta_List_Icon';
    protected static $mapperTable = 'Db_Mapper_CalendarIcon';

    /**
     * тег присутсвующий для всех
     * 
     * @var <type>
     */
    protected static $tag = 'Sancta_Peer_Icon';
    
    static protected $cachedMethods = array(      
      
        'getAll' => array(
            'index' => 'SP_Icon_getAll_list',
            'tags' => array('SP_Icon_list')
        ),
    );
}