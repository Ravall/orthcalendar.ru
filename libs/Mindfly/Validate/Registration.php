<?php
    /**
     * Валидатор для регистрации пользователя в системе
     **/
    require_once 'Zend/Validate.php';
    require_once 'Zend/Validate/EmailAddress.php';


    class Mindfly_Validate_Registration extends  Zend_Validate_Abstract
    {
        const EMAIL_NAME_INVALID = 'invalidEmail';
        const EMAIL_ALREADY_EXIST = 'emailAlreadyExist';
        const EMAIL_NOT_VALIDE = 'emailInValide';

        protected $_messageTemplates = array(
            self::EMAIL_NAME_INVALID => self::EMAIL_NAME_INVALID,
            self::EMAIL_ALREADY_EXIST => self::EMAIL_ALREADY_EXIST,
            self::EMAIL_NOT_VALIDE => self::EMAIL_NOT_VALIDE,
            
        );


        public function isValid($email, $context = null) {
            // проверим что email валидируется
            $validator = new Zend_Validate_EmailAddress();
            if (!$validator->isValid($email)) {
                $this->_error(self::EMAIL_NOT_VALIDE);
                return false;
            }
            // проверяем есть ли в имени теги
            if (strip_tags(trim($email)) != $email) {
                $this->_error(self::EMAIL_NAME_INVALID);
                return false;
            }
            
            // проверяем есть ли в системе пользователь с таким email
            if (Sancta_User::isExistEmail($email)) {
                $this->_error(self::EMAIL_ALREADY_EXIST);
                return false;
            }
            return true;
      }
    }
?>