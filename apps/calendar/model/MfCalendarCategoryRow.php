<?php
require_once SYSTEM_PATH . '/model/SystemObjectLayerRow.php';

/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class MfCalendarCategoryRow extends SystemObjectLayerRow {

    /**
     * Получаем события категории и подкатегорий,
     * которые есть в указанных датах
     * @param <type> $year
     * @param <type> $month
     */
    public function getEventsNet($year, $month, $day, &$eventArray = array()) {
        $childrenIds = array($this->id);
        foreach ($this->getChildrens() as $child) {
            $childrenIds[] = $child->id;
        }
        
        // получаем события
        $eventClass = new MfCalendarEventTable();
        $events = $eventClass->getEvents($childrenIds);

        $eventIds = array();
        foreach ($events as $event) {
            $eventIds[] = $event->id;
            $eventArray[$event->id]= $event;
        }
        $netClass = new MfCalendarNetTable();
        $net = $netClass->getNet($year, $month, $day, $eventIds);
        return $net;
    }

    /**
     * получение событий
     * 
     * @return <type>
     */
    public function getEvents($status = false) {
        $eventClass = new MfCalendarEventTable();
        $select = $eventClass->select();
        $select->join('mf_system_object', $cond = 'mf_system_object.id = mf_calendar_event.id', '')
               ->where($cond) // на кой-то йух нужно повторить условие, ибо к джоину оно не цепляется почему-то
               ->where('mf_system_object.parent_id = ' . $this->id);
        if ($status) {
            $select->where('mf_system_object.status = ?', $status);
        }

        $events = $eventClass->fetchAll($select);
        return count($events) ? $events : array();
    }

    /**
     * получаем список событий включая подкатегории
     * @todo нужно оптимизировать
     * @return <type>
     */
    public function getEventsAll() {
        $array = array();
        foreach ($this->getChildrens() as $child) {
            foreach ($child->getEvents() as $event) {
                $array[] = $event;
            }  
        }
        return $array;
    }

    /**
     * Добавляем событие к категории
     * 
     * @return MfCalendarEvent
     */
    public function addEvent() {
        //регистрируем объект
        $object = MfSystemObjectTable::register(array(
            'created_class' => 'mf_calendar_event',
            'parent_id' => $this->id
        ));
        $eventClass = new MfCalendarEventTable();
        $event = $eventClass->createRow();        
        $event->id = $object->id;
        $event->save();
        return $event;
    }

    /**
     * получаем список дочерних категорий
     */
    public function getChildrens($allStatuses = false) {
        $array = array();
        $this->_getChildrens($this->id,$array, $allStatuses);
        return $array;
    }

    public function getEventsMap($year, $month, $arrayOfExceptionEventsIds = array()) {
         $arrayOfSubIds = array($this->id);
         foreach ($this->getChildrens() as $child) {
             $arrayOfSubIds[] = $child->id;
         }
         $sql = 'select count(*) cnt, day from mf_calendar_net net'
              . ' join mf_system_object ob on ob.id = net.event_id'
              . ' where year=' . $year . ' and month=' . $month
              . ' and ob.parent_id in (' . implode(',', $arrayOfSubIds) . ')';
         if (!empty ($arrayOfExceptionEventsIds)) {
             $sql.= ' and net.event_id not in (' . implode(',', $arrayOfExceptionEventsIds) . ')';
         }
         $sql.=' group by full_date';         
         $map = $this->getTable()->getAdapter()->fetchAll($sql);
         $array = array();
         if (!empty ($map)) {
             foreach ($map as $item) {
                 $array[$item['day']] = $item['cnt'];
             }
         }
         return $array;
    }

    private function _getChildrens($id, &$array, $allStatuses = false) {
        $select = $this->select()
             ->join(array('so' => 'mf_system_object'), null, null)
             ->where('so.id = mf_calendar_category.id')             
             ->where('so.created_class =?','mf_calendar_category')
             ->where('so.parent_id = ?',$id);

        if (!$allStatuses) {
            $select->where('so.status = ?', STATUS_ACTIVE);
        }
        $childrens = $this->getTable()->fetchAll($select);
        foreach ($childrens as $child) {
            $array[] = $child;
            $this->_getChildrens($child['id'], $array);
        }
    }



}
