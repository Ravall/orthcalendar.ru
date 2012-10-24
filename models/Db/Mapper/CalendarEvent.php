<?php
    require_once dirname(__FILE__) . '/Abstract.php';
    require_once PATH_BASE . '/models/Db/Gateway/CalendarEvent.php';

    class Db_Mapper_CalendarEvent extends Db_Mapper_Abstract {
        public function  __construct() {
            $this->setDbTable('Db_Gateway_CalendarEvent');
        }

        public function create($params) {
            $row = $this->getDbTable()->createRow();
            $row->mfsystemobject_ptr_id = $params['object_id'];
            // $row->periodicity = (int) $params['periodicity'];
            $row->function_id = $params['function_id'];
            $row->save();
            return $row;
        }


        public function getAll() {
            $sql = 'SELECT event.id FROM mf_calendar_event event';
            return $this->getDbTable()->getAdapter()->fetchAll($sql);
        }



        public function getReloadedEvents($limit) {
            $limit = (int) $limit;
            $sql = 'SELECT ev.id FROM mf_calendar_event ev'
                 . ' JOIN mf_system_object obj ON obj.id = ev.mfsystemobject_ptr_id '
                 . ' AND obj.status = ' . $this->getDbTable()->getAdapter()->quote(STATUS_ACTIVE)
                 . ' WHERE reload = 1 LIMIT ' . $limit;
            return $this->getDbTable()->getAdapter()->fetchAll($sql);
        }
    }