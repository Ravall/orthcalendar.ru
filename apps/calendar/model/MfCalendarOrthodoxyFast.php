<?php

/**
 * Модель таблица даты. 
 *
 * @author user
 */
class MfCalendarOrthodoxyFast {
    private $rediska;
    public function  __construct() {        
        $this->rediska = Rediska_Manager::get(REDIS_NAMESPACE);
    }    
    public function addDate($date, $eventId, $remark = null, $zIndex = 0) {        
        $redisKey = new Rediska_Key('remark:' . $date . ':max_index', array('rediska' =>  $this->rediska));
        
        if ($redisKey->getValue() === null || $zIndex > $redisKey->getValue()) {
            $redisKey->setValue($zIndex);
        }        
        $redisKey = new Rediska_Key('remark:' . $date . ':' . $zIndex, array('rediska' =>  $this->rediska));
        $redisKey->setValue(array('event_id' => $eventId, 'remark' => $remark));
    }
    public function getPostInfo($date) {
        $keyZIndex = new Rediska_Key('remark:' . $date . ':max_index', array('rediska' =>  $this->rediska));
        $key = new Rediska_Key('remark:' . $date . ':' . $keyZIndex->getValue(), array('rediska' =>  $this->rediska));
        return $key->getValue();
    }
}