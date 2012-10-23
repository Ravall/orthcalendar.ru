<?php
/* 
 * Класс описывающий методы запоминания
 * и извлечение даты события
 *
 * используется Redis
 */

/**
 * Description of MfCalendarEventDate
 *
 * @author ravall
 */
class MfCalendarEventDate {
    private $rediska;
    public function  __construct() {
        $this->rediska = Rediska_Manager::get(REDIS_NAMESPACE);
    }
    public function addDate($date, $eventId) {
        $mdate = new Mindfly_Date($date);
        $redisSet = new Rediska_Key_Set(
            'date_event:' . $mdate->getDay(),
            array('rediska' =>  $this->rediska)
        );
        if (!$redisSet->exists($eventId)) {
            $redisSet->add($eventId);    
        }
        $redisSet = new Rediska_Key_Set(
            'date_event:' . $mdate->getDateByFormat('Y-m'),
            array('rediska' =>  $this->rediska)
        );
        if (!$redisSet->exists($eventId)) {
            $redisSet->add($eventId);
        }
    }

    public function deleteDate($date, $eventId) {
        $mdate = new Mindfly_Date($date);
        $redisSet = new Rediska_Key_Set(
            'date_event:' . $mdate->getDay(),
            array('rediska' =>  $this->rediska)
        );
        if (!$redisSet->exists($eventId)) {
            $redisSet->remove($eventId);
        }
        $redisSet = new Rediska_Key_Set(
            'date_event:' . $mdate->getDateByFormat('Y-m'),
            array('rediska' =>  $this->rediska)
        );
        if (!$redisSet->exists($eventId)) {
            $redisSet->remove($eventId);
        }
    }

    public function getDates($year, $month = null, $day = null) {

    }
}
?>