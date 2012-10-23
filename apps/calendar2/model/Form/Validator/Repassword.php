<?php
    require_once 'Zend/Validate.php';
    class Calendar_Validate_Repassword extends  Zend_Validate_Abstract {
        const RE_PASSWORD_INVALIDE = 're_password_invalide';
        protected $_messageTemplates = array(
            self::RE_PASSWORD_INVALIDE => self::RE_PASSWORD_INVALIDE,
        );
        public function isValid($rePassword, $context = null) {
            if ($rePassword !== $context['reg_password'] ) {
                $this->_error(self::RE_PASSWORD_INVALIDE);
                return false;
            }
            return true;
      }
    }
?>