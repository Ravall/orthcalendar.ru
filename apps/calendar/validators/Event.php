<?php
   /**
     * Валидатор для даты события календаря календаря
     *  все принимается в timestamp
     **/
    require_once 'Zend/Validate.php';    

    class Validate_Calendar_Event extends  Zend_Validate_Abstract
    {
        const DATE_INVALID = 'invalidEventDate';
        
        protected $_messageTemplates = array(
            self::DATE_INVALID => self::DATE_INVALID,
        );

        public function isValid($type) {
            if (!$type) {
                return true;
            }
            // проверяем валидность даты
            if (!in_array($type, array(EVENT_TYPE_INFORMATIVE, EVENT_TYPE_PRACTIC))) {
                 $this->_error(self::DATE_INVALID);
                return false;
            }            
            return true;
        }


    }

?>