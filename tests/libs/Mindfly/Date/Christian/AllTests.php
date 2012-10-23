<?php
require_once dirname(__FILE__).'/FastTest.php';
require_once dirname(__FILE__).'/GreatFeastsTest.php';
require_once dirname(__FILE__).'/MeatfareTest.php';
require_once dirname(__FILE__).'/SemidnizaTest.php';
require_once dirname(__FILE__).'/SoulsDayTest.php';
require_once dirname(__FILE__).'/FortyDayFastTest.php';
require_once dirname(__FILE__).'/RemarkTest.php';
require_once dirname(__FILE__).'/SoulsDayTest.php';

class Mindfly_DateChristian_AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Project');        
        $suite->addTestSuite('Mindfly_Date_Christian_RemarkTest');
        $suite->addTestSuite('Mindfly_Date_Christian_FastTest');
        $suite->addTestSuite('Mindfly_Date_Christian_GreatFeastsTest');
        $suite->addTestSuite('Mindfly_Date_Christian_MeatfareTest');
        $suite->addTestSuite('Mindfly_Date_Christian_SemidnizaTest');
        $suite->addTestSuite('Mindfly_Date_Christian_SoulsDayTest');
        $suite->addTestSuite('Mindfly_Date_Christian_FortyDayFastTest');        
        $suite->addTestSuite('Mindfly_Date_Christian_SoulsDayTest');
        return $suite;
    }
}
?>