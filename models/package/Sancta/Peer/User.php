<?php
require_once SANCTA_PATH . '/Abstract/Peer.php';
require_once SANCTA_PATH . '/User.php';
require_once PATH_BASE . '/models/Db/Mapper/SystemUser.php';
require_once PATH_BASE . '/models/Db/Mapper/CalendarUserCategory.php';
require_once SANCTA_PATH . '/Abstract/Peer.php';
require_once SANCTA_PATH . '/Bundle/StatusesParam.php';

/**
 * Класс-контроллер для делегирования получения пользователей
 */
class Sancta_Peer_User extends Sancta_Abstract_Peer 
{
    protected static $className = 'Sancta_User';
    protected static $classListName = 'Sancta_List_User';
    protected static $mapperTable = 'Db_Mapper_User';

    /**
     * тег присутсвующий для всех
     *
     * @var <type>
     */
    protected static $tag = 'SP_User_list';
   
     /**
      * получение пользователя по его логину
      *
      * @param <type> $id
      * @return self
      */
    public static function getByLogin($email) 
    {
        $systemUserTable = new Db_Mapper_SystemUser();
        if (!$id = $systemUserTable->getIdByEmail($email)) {
            return false;
        }   
        $className = static::$className;
        return new $className((int) $id);                
    }

    /**
     * gmt actual
     * 
     * @param type $gmtActual 
     */
    public static function getUsersByGmtAndHaveAnySubscribe($gmtActual) 
    {
        $subscribeTable = new Db_Mapper_CalendarUserCategory();        
        $users = $subscribeTable->getUsersByGmtAndHaveAnySubscribe($gmtActual);
        $listClass = self::getClassListName();
        return new $listClass($users);
    }
     
}