<?php
require_once realpath(dirname(__FILE__).'/../../../../../') . '/config/init.php';
require_once PATH_TESTS . '/init.php';
require_once PATH_LIBS . '/Mindfly/Date/Christian/FortyDayFast.php';


class Mindfly_Date_Christian_FortyDayFastTest extends PHPUnit_Framework_TestCase {
    public function providerDays() {
        return array(
            array('sundayOfThePublicanAndPharisee','2011', '2011-02-13'),
            array('sundayOfThePublicanAndPharisee','2012', '2012-02-05'),
            array('sundayOfThePublicanAndPharisee','2013', '2013-02-24'),
            array('septuagesima','2011', '2011-02-20'),
            array('septuagesima','2012', '2012-02-12'),
            array('septuagesima','2013', '2013-03-03'),
            array('meatSaturdayRemembranceParents', '2010', '2010-02-06'),
            array('meatSaturdayRemembranceParents', '2011', '2011-02-26'),
            array('meatSaturdayRemembranceParents', '2012', '2012-02-18'),           
            array('sundayOfTheJudgementDay','2011', '2011-02-27'),
            array('sundayOfTheJudgementDay','2012', '2012-02-19'),
            array('sundayOfTheJudgementDay','2013', '2013-03-10'),
            array('quinquagesima','2011', '2011-03-06'),
            array('quinquagesima','2012', '2012-02-26'),
            array('quinquagesima','2013', '2013-03-17'),
            array('feastOfOrthodoxy','2011', '2011-03-13'),
            array('feastOfOrthodoxy','2012', '2012-03-04'),
            array('feastOfOrthodoxy','2013', '2013-03-24'),
            array('gregoryPalamas','2011', '2011-03-20'),
            array('gregoryPalamas','2012', '2012-03-11'),
            array('gregoryPalamas','2013', '2013-03-31'),
            array('sundayOfTheVenerationOfTheCross','2011', '2011-03-27'),
            array('sundayOfTheVenerationOfTheCross','2012', '2012-03-18'),
            array('sundayOfTheVenerationOfTheCross','2013', '2013-04-07'),
            array('johnClimacus','2011', '2011-04-03'),
            array('johnClimacus','2012', '2012-03-25'),
            array('johnClimacus','2013', '2013-04-14'),
            array('stationsMaryTheEgyptian','2011', '2011-04-07'),
            array('stationsMaryTheEgyptian','2012', '2012-03-29'),
            array('stationsMaryTheEgyptian','2013', '2013-04-18'),
            array('akathistusSaturday','2011', '2011-04-09'),
            array('akathistusSaturday','2012', '2012-03-31'),
            array('akathistusSaturday','2013', '2013-04-20'),
            array('maryTheEgyptian','2011', '2011-04-10'),
            array('maryTheEgyptian','2012', '2012-04-01'),
            array('maryTheEgyptian','2013', '2013-04-21'),
            array('lazarusSaturday','2011', '2011-04-16'),
            array('lazarusSaturday','2012', '2012-04-07'),
            array('lazarusSaturday','2013', '2013-04-27'),
            array('saturdayRemembranceParents', '2010', array('2010-02-27', '2010-03-06', '2010-03-13')),
            array('saturdayRemembranceParents', '2011', array('2011-03-19', '2011-03-26', '2011-04-02')),
            array('saturdayRemembranceParents', '2012', array('2012-03-10', '2012-03-17', '2012-03-24')),
        );
    }

    /**
     * @dataProvider providerDays
     * @param <type> $method
     * @param <type> $year
     * @param <type> $expeted
     */
    public function testDays($method,$year,$expeted) {
        $days = new Mindfly_Date_Christian_FortyDayFast($year);
        $this->assertEquals($expeted, $days->$method());
    }
}