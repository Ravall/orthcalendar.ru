<?php
require_once realpath(dirname(__FILE__).'/../../../../../') . '/config/init.php';
require_once PATH_TESTS . '/init.php';
require_once PATH_LIBS . '/Mindfly/Date/Christian/SoulsDay.php';


class Mindfly_Date_Christian_SoulsDayTest extends PHPUnit_Framework_TestCase {

    public function providerSoulsDay() {
        return array(
            
            array('dayOfRejoicing', '2010', '2010-04-13'),
            array('dayOfRejoicing', '2011', '2011-05-03'),
            array('dayOfRejoicing', '2012', '2012-04-24'),
            array('victoryDay', '2010', '2010-05-09'),
            array('victoryDay', '2011', '2011-05-09'),
            array('victoryDay', '2012', '2012-05-09'),
            array('trinitySaturdayRemembranceParents', '2010', '2010-05-22'),
            array('trinitySaturdayRemembranceParents', '2011', '2011-06-11'),
            array('trinitySaturdayRemembranceParents', '2012', '2012-06-02'),            
            array('dmitrySaturdayRemembranceParents', '2010', '2010-11-06'),
            array('dmitrySaturdayRemembranceParents', '2011', '2011-11-05'),
            array('dmitrySaturdayRemembranceParents', '2012', '2012-11-03'),

        );
    }

    /**
     * @dataProvider providerSoulsDay
     * @param <type> $year год
     * @param <type> $event событие (метод)
     * @param <type> $day ожидаемая дата
     */
    public function testSoulsDay($event, $year, $day) {
        $this->assertEquals($day, Mindfly_Date_Christian_SoulsDay::$event($year));
    }
}