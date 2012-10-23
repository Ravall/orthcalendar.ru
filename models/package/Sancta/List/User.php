<?php
require_once SANCTA_PATH . '/Abstract/List.php';
require_once SANCTA_PATH . '/User.php';
require_once SANCTA_PATH . '/Peer/User.php';

class Sancta_List_User extends Sancta_Abstract_List {
    protected $className = 'Sancta_User';    
    protected $classNamePeer = 'Sancta_Peer_User';    
    protected $listClass = 'Sancta_List_User';    
}
?>