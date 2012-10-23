<?php
require_once realpath(dirname(__FILE__).'/../../../../') . '/config/init.php';
require_once PATH_TESTS . '/init.php';
require_once PATH_LIBS . '/Mindfly/Date.php';
/**
 * Тестирование модуля даты
 *
 * @author ravall
 */
class Mindfly_Date_DateTest extends PHPUnit_Framework_TestCase {

    public function providerGetMonth() {
        return array(
            array('2010-07-15', '2010-07-01', '2010-07-31'),
            array('2010-06-11 23:59', '2010-06-01', '2010-06-30'),
            array('2009-06-11 23:59', '2009-06-01', '2009-06-30'),
            array('2010-02-11 23:59', '2010-02-01', '2010-02-28'),
            array('2008-02-11 23:59', '2008-02-01', '2008-02-29'),
            array('2008-02-01 00:00', '2008-02-01', '2008-02-29'),
            array('2008-03-31 23:59:59', '2008-03-01', '2008-03-31'),
        );
    }

    /**
     * тестируем функцию получения даты начала и конца месяца
     * 
     * @dataProvider providerGetMonth
     */
    public function testGetMonth($date, $Dateform, $Dateto) {
        $date = new Mindfly_Date($date);
        $dateExpected = new DateResult($Dateform, $Dateto);
        $this->assertEquals($date->getMoth(),$dateExpected);
    }


    public function providerGetDay() {
        return array(
          array('2010-01-01 23:23', '2010-01-01','2010', '01', '01'),
          array('2008-02-29 23:23', '2008-02-29','2008', '02', '29')
        );
    }



    /**
     * @dataProvider providerGetDay
     */
    public function testGetDay($dayIn, $dayOut, $y, $m, $d) {
        $day = new Mindfly_Date($dayIn);
        $this->assertEquals($day->getDay(), $dayOut);
        $this->assertEquals($day->getD(), $d);
        $this->assertEquals($day->getM(), $m);
        $this->assertEquals($day->getY(), $y);
    }


    public function providerGetYear() {
        return array(
            array('2010-07-15', '2010-01-01', '2010-12-31'),
            array('2010-06-11 23:59', '2010-01-01', '2010-12-31'),
            array('2009-06-11 23:59', '2009-01-01', '2009-12-31'),
            array('2010-02-11 23:59', '2010-01-01', '2010-12-31'),
            array('2008-02-11 23:59', '2008-01-01', '2008-12-31'),
            array('2008-02-01 00:00', '2008-01-01', '2008-12-31'),
            array('2008-03-31 23:59:59', '2008-01-01', '2008-12-31'),
        );
    }



    /**
     * @dataProvider providerGetYear
     */
    public function testGetYear($date, $Dateform, $Dateto) {
        $date = new Mindfly_Date($date);
        $dateExpected = new DateResult($Dateform, $Dateto);
        $this->assertEquals($date->getYear(),$dateExpected);
    }


    public function providerGetEmptyMonthMap() {
        $y2010m01 = array(
            '2010-01-01' => 0, '2010-01-02' => 0, '2010-01-03' => 0, '2010-01-04' => 0, '2010-01-05' => 0,
            '2010-01-06' => 0, '2010-01-07' => 0, '2010-01-08' => 0, '2010-01-09' => 0, '2010-01-10' => 0,
            '2010-01-11' => 0, '2010-01-12' => 0, '2010-01-13' => 0, '2010-01-14' => 0, '2010-01-15' => 0,
            '2010-01-16' => 0, '2010-01-17' => 0, '2010-01-18' => 0, '2010-01-19' => 0, '2010-01-20' => 0,
            '2010-01-21' => 0, '2010-01-22' => 0, '2010-01-23' => 0, '2010-01-24' => 0, '2010-01-25' => 0,
            '2010-01-26' => 0, '2010-01-27' => 0, '2010-01-28' => 0, '2010-01-29' => 0, '2010-01-30' => 0,
            '2010-01-31' => 0,
        );
        $y2010m02 = array(
            '2010-02-01' => 0, '2010-02-02' => 0, '2010-02-03' => 0, '2010-02-04' => 0, '2010-02-05' => 0,
            '2010-02-06' => 0, '2010-02-07' => 0, '2010-02-08' => 0, '2010-02-09' => 0, '2010-02-10' => 0,
            '2010-02-11' => 0, '2010-02-12' => 0, '2010-02-13' => 0, '2010-02-14' => 0, '2010-02-15' => 0,
            '2010-02-16' => 0, '2010-02-17' => 0, '2010-02-18' => 0, '2010-02-19' => 0, '2010-02-20' => 0,
            '2010-02-21' => 0, '2010-02-22' => 0, '2010-02-23' => 0, '2010-02-24' => 0, '2010-02-25' => 0,
            '2010-02-26' => 0, '2010-02-27' => 0, '2010-02-28' => 0,
        );
        $y2010m04 = array(
            '2010-04-01' => 0, '2010-04-02' => 0, '2010-04-03' => 0, '2010-04-04' => 0, '2010-04-05' => 0,
            '2010-04-06' => 0, '2010-04-07' => 0, '2010-04-08' => 0, '2010-04-09' => 0, '2010-04-10' => 0,
            '2010-04-11' => 0, '2010-04-12' => 0, '2010-04-13' => 0, '2010-04-14' => 0, '2010-04-15' => 0,
            '2010-04-16' => 0, '2010-04-17' => 0, '2010-04-18' => 0, '2010-04-19' => 0, '2010-04-20' => 0,
            '2010-04-21' => 0, '2010-04-22' => 0, '2010-04-23' => 0, '2010-04-24' => 0, '2010-04-25' => 0,
            '2010-04-26' => 0, '2010-04-27' => 0, '2010-04-28' => 0, '2010-04-29' => 0, '2010-04-30' => 0,
        );
        $y2008m02 = array(
            '2008-02-01' => 0, '2008-02-02' => 0, '2008-02-03' => 0, '2008-02-04' => 0, '2008-02-05' => 0,
            '2008-02-06' => 0, '2008-02-07' => 0, '2008-02-08' => 0, '2008-02-09' => 0, '2008-02-10' => 0,
            '2008-02-11' => 0, '2008-02-12' => 0, '2008-02-13' => 0, '2008-02-14' => 0, '2008-02-15' => 0,
            '2008-02-16' => 0, '2008-02-17' => 0, '2008-02-18' => 0, '2008-02-19' => 0, '2008-02-20' => 0,
            '2008-02-21' => 0, '2008-02-22' => 0, '2008-02-23' => 0, '2008-02-24' => 0, '2008-02-25' => 0,
            '2008-02-26' => 0, '2008-02-27' => 0, '2008-02-28' => 0, '2008-02-29' => 0,
        );

        return array(
            array('2010-01-01', $y2010m01),
            array('2010-01-15', $y2010m01),
            array('2010-02-01', $y2010m02),
            array('2010-04-01', $y2010m04),
            array('2008-02-01', $y2008m02),
        );
    }

    /**
     * @dataProvider providerGetEmptyMonthMap
     */
    public function testGetEmptyMonthMap($date, $map) {
        $data = new Mindfly_Date($date);
        $mapActual = $data->getEmptyMonthMap();
        $this->assertEquals($map, $mapActual);
    }



    public function providerGetDateByFormat() {
        return array(
            array('2010-10-11', 'd', '11'),
            array('2010-10-11', 'm', '10'),
            array('2010-10-11', 'Y', '2010'),
            array('2010-10-11', 'd.m', '11.10'),
        );
    }
    
    /**
     *
     * @dataProvider providerGetDateByFormat
     * @cover getDateByFormat
     */
    public function testGetDateByFormat($dataTime, $format, $expected) {
        $data = new Mindfly_Date($dataTime);
        $this->assertEquals($expected, $data->getDateByFormat($format));
    }


    /**
     * @cover nextDay
     */
    public function testNextDay() {
        $dataTime = '2010-04-01';
        $data = new Mindfly_Date($dataTime);
        $data->nextDay();
        $this->assertEquals('2010-04-02', $data->getDay());
        // проверим что ничего не портится,
        // если часто вызывать функцию
        for ($i=0; $i<365; $i++) {
            $data->nextDay();
        }
        $this->assertEquals('2011-04-02', $data->getDay());

        $data = new Mindfly_Date('2010-08-28');
        $dataArray = array();
        do {
            $dataArray[] = $data->getDay();
            $data->nextDay();
            
        } while ($data->getDay() != '2010-11-28');
        $this->assertEquals(92, count($dataArray), print_r($dataArray,true));

        $data = new Mindfly_Date('2010-08-28');
        $data->nextDay();
        $this->assertEquals($data->getD(), '29');
    }

    /**
     *
     */
    public function providerParseDateArrayToString() {
        return array(
            array(array(1,2,3,4,5),'1-5'),
            array(array(1,2,3,4,5,7,8,9,10,11),'1-5,7-11'),
            array(array(1,2,3,4,5,7,9),'1-5,7,9'),
            array(array(1,3,5,7,9),'1,3,5,7,9'),
            array(array(1,3,5,7,9,10,11,12,13),'1,3,5,7,9-13'),
        );
    }
    
    /**
     * @dataProvider providerParseDateArrayToString
     * @param <type> $array
     * @param <type> $exceptedString 
     */
    public function testParseDateArrayToString($array, $exceptedString) {
        $this->assertEquals(
                $exceptedString, Mindfly_Date::parseDateArrayToString($array),
                ' не правильно распарсилось'
        );
    }
    
    
    public function providerGetLastDayPrevMonth() {
        return array(
            array('2010-12-05', '2010-11-30'),
            array('2011-01-15', '2010-12-31'),
            array('2010-03-17', '2010-02-28')
        );
    }

    /**
     * @dataProvider providerGetLastDayPrevMonth
     */
    public function testGetLastDayPrevMonth($date,$dateExpected) {
        $mDate = new Mindfly_Date($date);
        $this->assertEquals($dateExpected, $mDate->getLastDayPrevMonth()->getDay());
    }

}
?>
