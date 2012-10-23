<?php
require_once SANCTA_PATH . '/Abstract/List.php';
require_once SANCTA_PATH . '/Template.php';
require_once SANCTA_PATH . '/Peer/Template.php';

class Sancta_List_Template extends Sancta_Abstract_List {
    protected $className = 'Sancta_Template';    
    protected $classNamePeer = 'Sancta_Peer_Template';    
    protected $listClass = 'Sancta_List_Template';    
}