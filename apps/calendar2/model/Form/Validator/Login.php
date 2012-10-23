<?php
    /**
     * Валидатор для регистрации пользователя в системе
     **/
    require_once 'Zend/Validate.php';
    require_once 'Zend/Validate/EmailAddress.php';


    class Calendar_Validate_Login extends  Zend_Validate_Abstract
    {

        const EMAIL_NOT_VALIDE = 'emailInValide';
     
        protected $_messageTemplates = array(
            self::EMAIL_NOT_VALIDE => self::EMAIL_NOT_VALIDE,
        );


        public function isValid($email, $context = null) {
            // проверим что email валидируется
            $validator = new Zend_Validate_EmailAddress();
            if (!$validator->isValid($email)) {
                $this->_error(self::EMAIL_NOT_VALIDE);
                return false;
            }
            return true;
      }
    }
?>