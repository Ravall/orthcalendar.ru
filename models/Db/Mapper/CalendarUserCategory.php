<?php
    require_once dirname(__FILE__) . '/Abstract.php';
    require_once PATH_BASE . '/models/Db/Gateway/CalendarUserCategory.php';

    class Db_Mapper_CalendarUserCategory extends Db_Mapper_Abstract {
        public function  __construct() {
            $this->setDbTable('Db_Gateway_CalendarUserCategory');
        }

        public function isSubscribe($userId, $categoryId) {
            $select = $this->getDbTable()->getAdapter()->select('count (*)')
                      ->from('mf_calendar_user_category')
                      ->where(' user_id = ' . $this->getDbTable()->getAdapter()->quote($userId)
                            . ' and category_id = ' . $this->getDbTable()->getAdapter()->quote($categoryId));
            return (bool) $select->query()->fetchColumn();
        }

        public function subscribe($userId, $categoryId) {
            $row = $this->getDbTable()->createRow();
            $row->user_id = $userId;
            $row->category_id = $categoryId;
            $row->save();
        }

        public function unsubscribe($userId, $categoryId) {
            $where = array(
                $this->getDbTable()->getAdapter()->quoteInto('user_id = ?', $userId),
                $this->getDbTable()->getAdapter()->quoteInto('category_id = ?', $categoryId)
            );
            $this->getDbTable()->delete($where);
        }

        public function getUsersByGmtAndHaveAnySubscribe($gmt) {
            $gmt = $this->getDbTable()->getAdapter()->quote($gmt);
            $gmtLess = floatval($gmt - 0.5);
            $gmtMore = $gmt + 0.5;
           
            $result =  $this->getDbTable()->getAdapter()->fetchCol($sql =
                "SELECT DISTINCT u.id FROM mf_calendar_user_category uc "
              . " JOIN mf_system_user u ON uc.user_id = u.id WHERE gmt > {$gmtLess} and gmt < {$gmtMore}" 
            );            
            return $result;
        }

        public function getSubsribe($userId) {
            return $this->getDbTable()->getAdapter()->fetchCol(
               'SELECT category_id FROM mf_calendar_user_category WHERE user_id = '
              . $this->getDbTable()->getAdapter()->quote($userId)
            );
        }

        public function setDeliveryDone($userId, $categoryId, $date) {
            $this->getDbTable()->getAdapter()->query(
                'UPDATE mf_calendar_user_category set last_delivery = '
              . $this->getDbTable()->getAdapter()->quote($date)
              . ' WHERE  user_id = ' . $this->getDbTable()->getAdapter()->quote($userId)
              . ' AND category_id = ' . $this->getDbTable()->getAdapter()->quote($categoryId)
            );
        }

        public function isDeliveryAlreadySend($userId, $categoryId, $date) {
            $select = $this->getDbTable()->getAdapter()->select('count (*)')
                      ->from('mf_calendar_user_category')
                      ->where(' user_id = ' . $this->getDbTable()->getAdapter()->quote($userId)
                            . ' and category_id = ' . $this->getDbTable()->getAdapter()->quote($categoryId)
                            . ' and last_delivery = '  . $this->getDbTable()->getAdapter()->quote($date));
            return (bool) $select->query()->fetchColumn();
        }

    }