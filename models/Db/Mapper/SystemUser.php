<?php
    require_once dirname(__FILE__) . '/Abstract.php';
    require_once PATH_BASE . '/models/Db/Gateway/SystemUser.php';

    class Db_Mapper_SystemUser extends Db_Mapper_Abstract {
        public function  __construct() {
            $this->setDbTable('Db_Gateway_SystemUser');
        }

        /**
         * проверяем существование email в системе
         * @param <type> $email
         */
        public function isExistEmail($email, $idCurrentUser = 0) {
            $select = $this->getDbTable()->getAdapter()->select('count (*)')
                      ->from('mf_system_user')
                      ->where('email = ' . $this->getDbTable()->getAdapter()->quote($email)
                            . ' and id != ' . $idCurrentUser);
            return (bool) $select->query()->fetchColumn();
       }


       /**
        * создание пользователя
        * 
        * @param type $params
        * @return type 
        */
       public function create($params) {
           $row = $this->getDbTable()->createRow();
           $row->id = $params['object_id'];      
           $row->email = $params['login'];
           $row->pass = md5($params['pass']);
           $row->save();
           return $row;
       }

       /**
        * получение записи по email
        * 
        * @param <type> $email
        * @return <type>
        */
       public function getIdByEmail($email) {
           $sql = 'select id from mf_system_user where email = ' . $this->quote($email);
           $row = $this->getDbTable()->getAdapter()->fetchRow($sql);
           return $row['id'];
       }
       
     
       public function createHash($id, $hash) {
           $user = $this->getDbTable()->find($id)->current();
           $user->hash = $hash;
           $user->hash_create = new Zend_Db_Expr('NOW()');
           $user->save();
       }

    


       
 
    }