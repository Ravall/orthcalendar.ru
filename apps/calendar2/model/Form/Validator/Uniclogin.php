<?php
    /**
     * Валидатор для регистрации пользователя в системе
     **/
    require_once 'Zend/Validate.php';
    class Calendar_Validate_Uniclogin extends  Zend_Validate_Abstract {
        const EMAIL_NOT_UNIC = 'emailNotUnic';
        protected $_messageTemplates = array(
            self::EMAIL_NOT_UNIC => self::EMAIL_NOT_UNIC,
        );
        public function isValid($email, $context = null) {
            // проверим что email валидируется
            $user = Sancta_Peer_User::getByLogin($email);
            if ($user && $user->getId() != $context['userid']) {
                $this->_error(self::EMAIL_NOT_UNIC);
                return false;
            }
            return true;
      }
    }
?>