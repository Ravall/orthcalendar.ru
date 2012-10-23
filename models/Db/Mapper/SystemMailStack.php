<?php
    require_once dirname(__FILE__) . '/Abstract.php';
    require_once PATH_BASE . '/models/Db/Gateway/SystemMailStack.php';

    class Db_Mapper_SystemMailStack extends Db_Mapper_Abstract {
        public function  __construct() {
            $this->setDbTable('Db_Gateway_SystemMailStack');
        }

        public function getNotSendMail($limit) {
            return $this->getDbTable()->fetchAll($this->getDbTable()->select()->where('is_send = ?', '0')->limit($limit));
        }

        public function setSended($id) {
            $where = $this->getDbTable()->getAdapter()->quoteInto('id = ?', $id);
            return $this->getDbTable()->update(array('is_send' => 1), $where);
        }

        public function addMail($to, $subject, $text) {
            $mail = $this->getDbTable()->createRow();
            $mail->to = $to;
            $mail->text = $text;
            $mail->subject = $subject;
            $mail->save();
            return $mail->id;
        }

    }