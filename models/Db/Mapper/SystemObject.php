<?php
    require_once dirname(__FILE__) . '/Abstract.php';
    require_once PATH_BASE . '/models/Db/Gateway/SystemObject.php';

    class Db_Mapper_SystemObject extends Db_Mapper_Abstract {
        public function  __construct() {
            $this->setDbTable('Db_Gateway_SystemObject');
        }
        /**
         * регистрация объекта
         *
         * @param <type> $params
         */
        public function create($params = array()) {
            // обязательным должен быть параметр created_class
            if (!$params['created_class']) {
                throw new Exception('created_class is null');
            }
            $params = array_merge(
                $params,
                array('created' => new Zend_Db_Expr('NOW()'))
            );            
            $row = $this->getDbTable()->createRow($params);
            $row->save();
            return $row;
        }

        public function setStatus($objectId, $status) {
            $object = $this->getDbTable()->find($objectId)->current();            
            $object->status = $status;
            $object->save();
            return $object;
        }

        public function setImage($objectId, $imageName) {
            $object = $this->getDbTable()->find($objectId)->current();
            $object->image = $imageName;
            $object->save();
            return $object;
        }
    }