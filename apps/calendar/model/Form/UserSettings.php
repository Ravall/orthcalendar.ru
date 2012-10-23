<?php
require_once PATH_BASE . '/models/Filters/Calendar/StringToDate.php';
require_once PATH_BASE . '/models/package/Additional/GeoLocation.php';

/**
 * Форма управления пользовательскими данными
 */
class Form_Calendar_UserSettings extends Zend_Form {
    public function  __construct() {
        parent::__construct(new Zend_Config_Ini(CALENDAR_PATH . '/config/forms.ini','userSettings'));
    }

    public function setUser($user) {
        $this->setCountryId($user->getCountryId());        
        $this->setRegionId($user->getRegionId());

        list($userYear, $userMonth, $userDay) =
        $user->getBirthday() ? explode('-', $user->getBirthday()): array(0,0,0);
        $this->getElement('country')->addMultiOptions(Additional_GeoLocation::getCountries());
        $ortodoxyConfig = new Zend_Config_Ini(CALENDAR_PATH . '/config/ortodoxy.ini');
        $this->setDefaults(array(
            'email' => $user->getLogin(),
            'year' => ($userYear == '0000' ? '' : $userYear),
            'month' => $userMonth,
            'country' => $user->getCountryId(),
            'region' => $user->getRegionId(),
            'city'  => $user->getCityId(),
            'mycity' => $user->getMyCity(),
            'gmt' => $user->getGmt(),
            'day' => $userDay,
            'userid' => $user->getId(),
            'isorthodoxy' => $user->isSubscribe($ortodoxyConfig->categoryId)
        ));        
    }

    public function setCountryId($countryId) {        
        if ($countryId = (int) $countryId) {            
            $this->getElement('region')->addMultiOptions(Additional_GeoLocation::getRegions($countryId));
        }
    }

    public function setRegionId($regionId) {        
        if ($regionId = (int) $regionId) {            
            $this->getElement('city')->addMultiOptions(Additional_GeoLocation::getCities($regionId));
        }
    }

    public function init() {
        $this->setDecorators(array(
            array('ViewScript', array('viewScript' => 'forms/user_settings.phtml'))
        ));
    }
}