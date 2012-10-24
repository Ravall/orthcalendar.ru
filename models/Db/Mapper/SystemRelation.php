<?php
    require_once dirname(__FILE__) . '/Abstract.php';
    require_once PATH_BASE . '/models/Db/Gateway/SystemRelation.php';

    class Db_Mapper_SystemRelation extends Db_Mapper_Abstract {

        public function  __construct() {
            $this->setDbTable('Db_Gateway_SystemRelation');
        }

        /**
         * связываение в отношение $objectId и $parentObjectId
         * @param <type> $objectId
         * @param <type> $parentObjectId
         * @return <type>
         */
        public function relate($objectId, $parentObjectId, $type) {
            $relate = $this->getDbTable()->createRow();
            $relate->mf_object_id = $objectId;
            $relate->parent_object_id = $parentObjectId;
            $relate->relation_id = $type;
            $relate->save();
            return $relate;
        }


        public function setRelates($objectId, $array) {
            $this->beginTransaction();
            try {
                $where = $this->getDbTable()->getAdapter()->quoteInto('mf_object_id = ?', $objectId);
                $this->getDbTable()->delete($where);
                foreach ($array as $parentObjectId) {
                    $this->relate($objectId, $parentObjectId);
                }
                $this->commitTransaction();
            } catch (Exception $e) {
                $this->rollBackTransaction();
                throw $e;
            }
            return true;
        }

        public function getRelatedObjectsByParentId($parentId, $relationType = 1) {
            $parentId = (int) $parentId;
            $sql = "select mf_object_id id from mf_system_relation"
                 . " where parent_object_id = {$parentId} "
                 . " and relation_id = {$relationType}";
            return $this->getDbTable()->getAdapter()->fetchAll($sql);
        }

        public function findById($id) {
            return $this->getDbTable()->fetchAll(array('mf_object_id = ?' => $id));
        }


    }