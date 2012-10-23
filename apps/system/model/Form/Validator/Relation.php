<?php
class Admin_Validate_Relation extends Zend_Validate_Abstract
{
    const INVALID_EVENT_ID = 'invalid_event_id';

    protected $_messageTemplates = array(
            self::INVALID_EVENT_ID => 'Некорректный идентификатор события "%value%" ',
    );

    public function isValid($str) {
        $strArray = explode(',', $str);
        array_walk($strArray, create_function('&$val', '$val = trim($val);'));
        foreach ($strArray as $eventId) {
            /**
             * так же целесообразно проверить сущенствование данного события
             */
            if ((int) $eventId == 0) {
                $this->_error(self::INVALID_EVENT_ID, $eventId);
                return false;
            }
        }
        return true;
    }
}