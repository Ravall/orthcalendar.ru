<?php
    require_once dirname(__FILE__) . '/../Sancta/SanctaTestCase.php';
    require_once PATH_BASE . '/models/package/Additional/GeoLocation.php';

    class GeoLocationTest extends SanctaTestCase {
        public function testGetCountries() {
            $countries = Additional_GeoLocation::getCountries();
            foreach ($countries as $id => $country) {
                $row = $this->db->fetchRow('select * from mf_system_countries where country_id = ' . $id);
                $this->assertEquals($row['title'], $country);
            }
        }

        public function testGetRegions() {
            $countries = Additional_GeoLocation::getCountries();
            foreach ($countries as $id => $country) {
                $regions = Additional_GeoLocation::getRegions($id);
                foreach ($regions as $idr => $region) {
                    $row = $this->db->fetchRow('select * from mf_system_regions where region_id = ' . $idr);
                    $this->assertEquals($row['title'], $region);
                }
            }
        }

        public function testGetCities() {
            $countries = Additional_GeoLocation::getCountries();
            foreach ($countries as $id => $country) {
                $regions = Additional_GeoLocation::getRegions($id);
                foreach ($regions as $idr => $region) {
                    $cities = Additional_GeoLocation::getCities($idr);
                    foreach ($cities as $ids => $city) {
                        $row = $this->db->fetchRow('select * from mf_system_cities where city_id = ' . $ids);
                        $this->assertEquals($row['title'], $city);
                    }
                }
            }
        }
    }