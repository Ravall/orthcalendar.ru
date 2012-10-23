<?php
    /**
     * Валидатор для категории календаря
     **/
    require_once 'Zend/Validate.php';
    require_once 'Zend/Validate/StringLength.php';
    
    
    class Mindfly_Validate_Calendar_Category extends  Zend_Validate_Abstract
    {
        const CATEGORY_NAME_INVALID = 'invalidCateogyName';
        const TOO_SHORT = "cateogyLengthTooShort";
        const TOO_LONG  = "cateogyLengthTooLong";

        const CATEGORY_NAME_ALREADY_EXIST = 'categoryAlreadyExist';
        const MIN_STRING_LENGTH = 2;
        const MAX_STRING_LENGTH = 50;

        protected $_messageTemplates = array(
            self::CATEGORY_NAME_INVALID => self::CATEGORY_NAME_INVALID,
            self::CATEGORY_NAME_ALREADY_EXIST => self::CATEGORY_NAME_ALREADY_EXIST,
            Zend_Validate_StringLength::TOO_SHORT => self::TOO_SHORT,
            Zend_Validate_StringLength::TOO_LONG => self::TOO_LONG,
            Zend_Validate_StringLength::INVALID => self::CATEGORY_NAME_INVALID
        );

        
        public function isValid($categoryName, $context = null) {
            $validator = new Zend_Validate_StringLength(self::MIN_STRING_LENGTH, self::MAX_STRING_LENGTH);            
            $validator->setEncoding('UTF-8');
            // проверяем валидность имени            
            if (!$validator->isValid($categoryName)) {                
                foreach ( $validator->getMessages() as $key => $mess) {                    
                    $this->_error($key);
                }                
                return false;
            }
            // проверяем есть ли в имени теги
            if (strip_tags(trim($categoryName)) != $categoryName) {
                $this->_error(self::CATEGORY_NAME_INVALID);
                return false;
            }
            

            $categoryId = isset($context['id'])?$context['id']:null;

            $category = new MfCalendarCategoryTable();
            // проверяем есть ли в системе такая категория
            
            if ($category->isExist($categoryName, $categoryId)) {
                $this->_error(self::CATEGORY_NAME_ALREADY_EXIST);
                return false;
            }            
            return true;
        }


    }

?>