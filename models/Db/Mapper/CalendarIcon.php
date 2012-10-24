<?php
    require_once dirname(__FILE__) . '/Abstract.php';
    require_once PATH_BASE . '/models/Db/Gateway/CalendarIcon.php';

    class Db_Mapper_CalendarIcon extends Db_Mapper_Abstract {
        public function  __construct() {
             $this->setDbTable('Db_Gateway_CalendarIcon');
        }

        public function create($params) {
            $row = $this->getDbTable()->createRow();
            $row->mfsystemobject_ptr_id = $params['object_id'];
            $row->object_id = $params['object_id'];
            $row->save();
            return $row;
        }

        public function getAll() {
            $sql = 'SELECT icon.id FROM mf_calendar_icon icon
                    JOIN mf_system_object object ON object.id = icon.mfsystemobject_ptr_id';
            return $this->getDbTable()->getAdapter()->fetchAll($sql);
        }
    }
