<?php
    require_once dirname(__FILE__) . '/Abstract.php';
    require_once PATH_BASE . '/models/Db/Gateway/SystemObjectText.php';

    class Db_Mapper_SystemObjectText extends Db_Mapper_Abstract {

        public function  __construct() {
            $this->setDbTable('Db_Gateway_SystemObjectText');
        }

        /**
         * получаем текст
         * 
         * @param <type> $id object_id
         * @param <type> $status статус (active,draft)
         * @return <type> 
         */
        public function getText($id, $status = 'active') {            
             $textObjectRowset = $this->getDbTable()->fetchAll(array(
                'system_object_id = ?' => $id,
                'status = ?' => $status
             ));
            $textObject = $textObjectRowset->current();
            
            return $textObject ? $textObject->findParentRow('Db_Gateway_SystemText'):false;
        }

        /**
         * связывает текст с объектом и языком.
         * 
         * @param <type> $objectId
         * @param <type> $textId
         * @param <type> $status
         * @return <type>
         */
        public function joinText($objectId, $textId, $status) {
            $objectTextRow = $this->getDbTable()->createRow();
            $objectTextRow->status = $status;
            $objectTextRow->system_object_id = $objectId;
            $objectTextRow->system_text_id = $textId;
            $objectTextRow->save();
            return $objectTextRow;
        }
    }
