<?php
    require_once dirname(__FILE__) . '/Abstract.php';
    require_once PATH_BASE . '/models/Db/Gateway/CalendarRemark.php';

    class Db_Mapper_CalendarRemark extends Db_Mapper_Abstract {

        public function  __construct() {
            $this->setDbTable('Db_Gateway_CalendarRemark');
        }
        
        public function getAll() {
            $sql = 'SELECT remark.id FROM mf_calendar_remark remark';
            return $this->getDbTable()->getAdapter()->fetchAll($sql);
        }


        public function getRemarkIdByEventId($eventId) {
            $sql = 'SELECT remark.id FROM mf_calendar_remark remark '
                  .' WHERE remark.event_id = '. $this->quote($eventId);
            return $this->getDbTable()->getAdapter()->fetchAll($sql);
        }


        public function create($params) {
            $row = $this->getDbTable()->createRow();
            $row->id = $params['object_id'];
            $row->event_id = $params['event_id'];
            $row->function_id = $params['function_id'];
            if (isset($params['priority'])) {
                $row->priority = $params['priority'];
            }
            $row->save();
            return $row;            
        }
        
       
        
        
        
    }