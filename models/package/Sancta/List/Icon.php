<?php
require_once SANCTA_PATH . '/Abstract/List.php';
require_once SANCTA_PATH . '/Icon.php';
require_once SANCTA_PATH . '/Peer/Icon.php';

class Sancta_List_Icon extends Sancta_Abstract_List {
    protected $className = 'Sancta_Icon';    
    protected $classNamePeer = 'Sancta_Peer_Icon';    
    protected $listClass = 'Sancta_List_Icon';    
}