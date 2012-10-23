<?php
require_once realpath(dirname(__FILE__).'/../') . '/config/init.php';
require_once PATH_TESTS . '/init.php';
require_once 'CleanTest.php';
require_once 'calendar/AllTest.php';
require_once 'system/AllTest.php';

require_once realpath(dirname(__FILE__)) . '/libs/AllTests.php';



class AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Project');
        // первичные тесты чистой базы
        $suite->addTestSuite('CleanTest');
        
        // системные тесты
        $suite->addTest(System_AllTests::suite());
        // тесты календаря
        $suite->addTest(Calendar_AllTests::suite());
        // тесты библиотек
        $suite->addTestSuite(libs_AllTests::suite());
        
        
        $suite->addTestSuite('CleanTest');
        return $suite;
    }
}
?>