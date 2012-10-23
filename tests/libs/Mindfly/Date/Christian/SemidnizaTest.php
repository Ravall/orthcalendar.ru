<?php
require_once realpath(dirname(__FILE__).'/../../../../../') . '/config/init.php';
require_once PATH_TESTS . '/init.php';
require_once PATH_LIBS . '/Mindfly/Date/Christian/Semidniza.php';
require_once PATH_LIBS . '/Mindfly/Date/Christian/Fast.php';


class Mindfly_Date_Christian_SemidnizaTest extends PHPUnit_Framework_TestCase {

    private $tableSemidniza;

    public function  setUp() {
        parent::setUp();
        $this->tableSemidniza = new Mindfly_Date_Christian_Semidniza();
    }


    public function providerSedmiza() {
        return array(
            array('christmasWeek', '2011', '2011-01-07', '2011-01-18'),
            array('christmasWeek', '2012', '2012-01-07', '2012-01-18'),
            array('christmasWeek', '2013', '2013-01-07', '2013-01-18'),
            array('taxCollectorAndPhariseeWeek', '2011', '2011-02-14', '2011-02-20'),
            array('taxCollectorAndPhariseeWeek', '2012', '2012-02-06', '2012-02-12'),
            array('taxCollectorAndPhariseeWeek', '2013', '2013-02-25', '2013-03-03'),
            array('carnivalWeek', '2011', '2011-02-28', '2011-03-06'),
            array('carnivalWeek', '2012', '2012-02-20', '2012-02-26'),
            array('carnivalWeek', '2013', '2013-03-11', '2013-03-17'),
            array('eastertideWeek', '2011', '2011-04-24', '2011-04-30'),
            array('eastertideWeek', '2012', '2012-04-15', '2012-04-21'),
            array('eastertideWeek', '2013', '2013-05-05', '2013-05-11'),
            array('whitsunWeek', '2011', '2011-06-13', '2011-06-19'),
            array('whitsunWeek', '2012', '2012-06-04', '2012-06-10'),
            array('whitsunWeek', '2013', '2013-06-24', '2013-06-30'),
            array('sexagesima', '2011', '2011-02-21','2011-02-27'),
            array('sexagesima', '2012', '2012-02-13','2012-02-19'),
            array('sexagesima', '2013', '2013-03-04','2013-03-10'),
            array('passionSunday', '2011', '2011-04-18','2011-04-23'),
            array('passionSunday', '2012', '2012-04-09','2012-04-14'),
            array('passionSunday', '2013', '2013-04-29','2013-05-04'),
        );
    }

    /**
     * Тест
     * 
     * @dataProvider providerSedmiza
     */
    public function testSedmiza($sedmiza, $year, $dateFrom, $dateTo) {
        $resultActual = $this->tableSemidniza->$sedmiza($year);
        $date = new Mindfly_Date($dateFrom);
        do {
            $excepted[] = $date->getDay();            
        } while ($date->nextDay()->getDay() <= $dateTo);
        $this->assertEquals($excepted, $resultActual);
    }




   

}

?>