<?php
/*
CREATE TABLE `mf_system_countries` (
  `country_id` INT(10) UNSIGNED NOT NULL,
  `title` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`country_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE `mf_system_regions` (
  `region_id` INT(10) UNSIGNED NOT NULL,
  `country_id` INT(10) UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`region_id`),
  KEY `FK_mf_system_regions` (`country_id`),
  CONSTRAINT `FK_mf_system_regions` FOREIGN KEY (`country_id`) REFERENCES `mf_system_countries` (`country_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE `mf_system_cities` (
  `city_id` INT(10) UNSIGNED NOT NULL,
  `region_id` INT(10) UNSIGNED NOT NULL,
  `country_id` INT(10) UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`city_id`),
  KEY `FK_mf_system_cities` (`country_id`),
  KEY `FK_mf_system_cities2` (`region_id`),
  CONSTRAINT `FK_mf_system_cities2` FOREIGN KEY (`region_id`) REFERENCES `mf_system_regions` (`region_id`),
  CONSTRAINT `FK_mf_system_cities` FOREIGN KEY (`country_id`) REFERENCES `mf_system_countries` (`country_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;
*/
class Db_Gateway_SystemCountries extends Zend_Db_Table_Abstract {
    protected $_name = 'mf_system_countries';
    protected $_primary = 'country_id';
}
?>