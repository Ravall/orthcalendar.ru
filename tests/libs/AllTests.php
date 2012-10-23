<?php
require_once dirname(__FILE__) . '/Mindfly/Date/DateTest.php';
require_once dirname(__FILE__) . '/Mindfly/Grammar/GrammarTest.php';
require_once dirname(__FILE__) . '/Validate/RegistrationTest.php';
require_once dirname(__FILE__) . '/Mindfly/Date/Christian/AllTests.php';

/**
 * Все тесты библиотек
 */
class libs_AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Project');
        $suite->addTestSuite('Validate_RegistrationTest');
        $suite->addTestSuite('Mindfly_Date_DateTest');
        $suite->addTestSuite('Mindfly_GrammarTest');
        $suite->addTestSuite(Mindfly_DateChristian_AllTests::suite());
        return $suite;
    }
}
?>