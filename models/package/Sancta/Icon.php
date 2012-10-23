<?php
require_once PATH_BASE . '/models/Db/Mapper/CalendarIcon.php';
require_once SANCTA_PATH . '/Abstract/System.php';
require_once SANCTA_PATH . '/Peer/Icon.php';
require_once PATH_BASE . '/models/Db/Mapper/SystemRelation.php';

class Sancta_Icon extends Sancta_Abstract_System {
    private $iconId;
    protected $mapperTable = 'Db_Mapper_CalendarIcon';

    const RELATE_TO_EVENT_TYPE = 2; # свзяь 

    protected function _setModel($icon) 
    {
        $this->iconId = $icon->id;     
    }

    public function relateToEvent($eventId)
    {
    	$systemRelationTable = new Db_Mapper_SystemRelation();
        $result =  $systemRelationTable->relate($this->getId(), $eventId, self::RELATE_TO_EVENT_TYPE);
        return $result;
    }

    public function getRelatedEvent() {
        return current($this->getRelatedObjets());
    }
}
?>