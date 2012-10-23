<?php
require_once SANCTA_PATH . '/Peer/User.php';

/**
 * Плагин авторизует пользователя, если заданы hash и id
 */
class myZend_Controller_Plugin_Loginbyhash extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch($request)
    {
        $id = $request->getParam('id');
        $hash = $request->getParam('hash');
         if ($id && $hash) {
            $result = self::loginByHash($id, $hash);
        }
    }
    
    /**
     * авторизация по user_id и хешу
     * @param <type> $userId
     * @param <type> $hash
     * @return <type>
     */
    public static function  loginByHash($userId, $hash) 
    {
        // Получить экземпляр Zend_Auth
        $auth = Zend_Auth::getInstance();
        //хеш должен быть сгенерирован сегодня
        $authAdapter = new Zend_Auth_Adapter_DbTable(
            null, 'mf_system_user', 'id', 'hash',
            'to_days(hash_create)-to_days(now()) = 0'
        );
        $authAdapter->setIdentity($userId)
                    ->setCredential($hash);
        if ($authAdapter->authenticate()->isValid()) {
            $user = Sancta_Peer_User::getById($authAdapter->getResultRowObject()->id);
            $auth = Zend_Auth::getInstance();
            $auth->getStorage()->write($user);
            return true;
        }
        return false;
    }
    
}