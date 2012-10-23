<?php
require_once SANCTA_PATH . '/Abstract/Peer.php';
require_once SANCTA_PATH . '/List/Article.php';
require_once PATH_BASE . '/models/Db/Mapper/SystemArticle.php';
require_once SANCTA_PATH . '/Abstract/Peer.php';
require_once SANCTA_PATH . '/Bundle/StatusesParam.php';

/**
 * Класс-контроллер для делегирования получения статей
 */
class Sancta_Peer_Article extends Sancta_Abstract_Peer {
    
    protected static $className = 'Sancta_Article';
    protected static $classListName = 'Sancta_List_Article';
    protected static $mapperTable = 'Db_Mapper_SystemArticle';

    /**
     * тег присутсвующий для всех
     * 
     * @var <type>
     */
    protected static $tag = 'Sancta_Peer_Article';

    
    static protected $cachedMethods = array(      
        'getByEventId' => array(
            'index' => 'SP_Article_getByEventId_list', 
            'tags' => array('SP_Article_list')
        ),   
        'getAll' => array(
            'index' => 'SP_Article_getAll_list',
            'tags' => array('SP_Article_list')
        ),
    );
    
     /**
      * получаем все статьи привязанные к событию
      * @param <type> $eventId
      * @return this
     */
     protected static function getByEventId($eventId) {        
        $systemRelationTable = new Db_Mapper_SystemRelation();            
        $parents = $systemRelationTable->getRelatedObjectsByParentId($eventId);                    
        $listClass = self::getClassListName();
        return new $listClass($parents,'id');        
    }
}