<?php
require_once SANCTA_PATH . '/Abstract/Peer.php';
require_once PATH_BASE . '/models/Db/Mapper/SystemCountries.php';
require_once PATH_BASE . '/models/Db/Mapper/SystemRegions.php';
require_once PATH_BASE . '/models/Db/Mapper/SystemCities.php';

class Sancta_Peer_Geo extends Sancta_Abstract_Peer {
    
    protected static $tag = 'SP_Geo';

    static protected $cachedMethods = array(      
        'getCountries' => array(
            'index' => 'SP_Geo_getCountries', 
            'tags' => array('SP_Geo_list')
        ),   
        'getRegions' => array(
            'index' => 'SP_Geo_getRegions',
            'tags' => array('SP_Geo_list')
        ),
        'getCities' => array(
            'index' => 'SP_Geo_getCities',
            'tags' => array('SP_Geo_list')
        ),
    );
    
    protected static function getCountries() {   
        $countries = new Db_Mapper_SystemCountries();
        $countryArray = array();
        foreach ($countries->getAll() as $country) {
            $countryArray[$country['country_id']] = $country['title'];
        }
        return $countryArray;
    }
    
    protected static function getRegions($countryId) {            
        $regions = new Db_Mapper_SystemRegions();
        $regionArray = array();
        foreach ($regions->getRegionsByCountry($countryId) as $region) {
            $regionArray[$region['region_id']] = $region['title'];
        }
        return $regionArray;
    }

    protected static function getCities($regionId) {            
        $cities = new Db_Mapper_SystemCities();
        $citiesArray = array();
        foreach ($cities->getCitiesByRegion($regionId) as $city) {
            $citiesArray[$city['city_id']] = $city['title'];
        }        
        return $citiesArray;
    }
}