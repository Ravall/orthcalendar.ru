<?php
require_once SANCTA_PATH . '/Abstract/Peer.php';
require_once SANCTA_PATH . '/List/Template.php';
require_once SANCTA_PATH . '/Template.php';
require_once PATH_BASE . '/models/Db/Mapper/SystemTemplate.php';
require_once SANCTA_PATH . '/Abstract/Peer.php';
require_once SANCTA_PATH . '/Bundle/StatusesParam.php';

/**
 * Класс-контроллер для делегирования получения шаблонов
 */
class Sancta_Peer_Template extends Sancta_Abstract_Peer {
    
    protected static $className = 'Sancta_Template';
    protected static $classListName = 'Sancta_List_Template';
    protected static $mapperTable = 'Db_Mapper_SystemTemplate';

    /**
     * тег присутсвующий для всех
     * 
     * @var <type>
     */
    protected static $tag = 'Sancta_Peer_Template';

    
    static protected $cachedMethods = array(      
        'getAll' => array(
            'index' => 'SP_Template_getAll',
            'tags' => array('SP_Template_list')
        ),
        'getByName' => array(
            'index' => 'SP_Template_getByName',
            'tags' => array('SP_Template_list')
        )        
    );
        
    /**
     * получение сырого шаблона.
     * 
     * 
     * @param type $name
     * @return type 
     */    
    protected static function getByName($name) {          
        $template = Sancta_Peer_Template::getById(
            Config_Interface::get($name, 'templates')
        );
        return $template;
    }
    
}