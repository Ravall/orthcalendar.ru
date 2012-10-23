<?php
require_once realpath(dirname(__FILE__).'/../') . '/../config/init.php';
require_once PATH_TESTS . '/init.php';
require_once realpath(dirname(__FILE__)) . '/model/CategoryTableTest.php';
require_once realpath(dirname(__FILE__)) . '/model/CategoryRowTest.php';
require_once realpath(dirname(__FILE__)) . '/model/EventTableTest.php';
require_once realpath(dirname(__FILE__)) . '/model/EventRowTest.php';
require_once realpath(dirname(__FILE__)) . '/model/NetRowTest.php';
require_once realpath(dirname(__FILE__)) . '/model/NetTableTest.php';
require_once realpath(dirname(__FILE__)) . '/validator/CategoryValidateTest.php';
require_once realpath(dirname(__FILE__)) . '/filters/StringToDateTest.php';

class Calendar_AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Calendar');
        // тесты моделей
        $suite->addTestSuite('CategoryTableTest');
        $suite->addTestSuite('CategoryRowTest');
        $suite->addTestSuite('EventTableTest');
        $suite->addTestSuite('EventRowTest');
        $suite->addTestSuite('NetTableTest');
        $suite->addTestSuite('NetRowTest');

        // тесты валидаторов
        $suite->addTestSuite('CategoryValidateTest');

        // тесты фильтров
        $suite->addTestSuite('StringToDateTest');


        return $suite;
    }
}
?>