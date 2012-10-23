<?php
require_once realpath(dirname(__FILE__).'/../../../') . '/config/init.php';
require_once PATH_TESTS . '/init.php';
require_once PATH_LIBS_ZEND . '/Zend/Filter/Interface.php';
require_once PATH_BASE . '/models/Filters/Calendar/StringToDate.php';

/**
 * Тест фильтра даты
 *
 * @author user
 */
class StringToDateTest extends PHPUnit_Framework_TestCase {

    public function providerFilter() {
        return array (
            array('3 января 2009', '2009-01-03'),
            array('13 января 2009', '2009-01-13'),
            array(' 13 января 2009', '2009-01-13'),
            array(' 13 января     2009', '2009-01-13'),
            array('1 февраля 2009', '2009-02-01'),
            array('1 марта 2009', '2009-03-01'),
            array('1 марта 2009', '2009-03-01'),
            array('1 апреля 2009', '2009-04-01'),
            array('1 мая 2009', '2009-05-01'),
            array('1 июня 2009', '2009-06-01'),
            array('1 июля 2009', '2009-07-01'),
            array('1 августа 2009', '2009-08-01'),
            array('1 сентября 2009', '2009-09-01'),
            array('1 октября 2009', '2009-10-01'),
            array('3 ноября 1983', '1983-11-03'),
            array('1 декабря 2009', '2009-12-01'),
            array('1 труляля 2009', false),
            array('fsdfsdfsd', false),
            array('', false),
            array(' ', false),
        );
    }

    /**
     * @dataProvider providerFilter
     * @param <type> $input
     * @param <type> $output
     */
    public function testFilter($input, $output) {
        $filter = new Filter_Calendar_StringToDate();
        $this->assertEquals($filter->filter($input), $output);
    }



        public function providerBack() {
        return array (
            array('2009-01-03', '3 января 2009'),
            array('2009-01-13', '13 января 2009' ),
            array(' 2009-01-13', '13 января 2009'),
            array('2009-01-13    ', '13 января 2009'),
            array('2009-02-01', '1 февраля 2009'),
            array('2009-03-01', '1 марта 2009'),
            array('2009-03-01', '1 марта 2009'),
            array('2009-04-01', '1 апреля 2009'),
            array('2009-05-01', '1 мая 2009'),
            array('2009-06-01', '1 июня 2009'),
            array('2009-07-01', '1 июля 2009'),
            array('2009-08-01', '1 августа 2009'),
            array('2009-09-01', '1 сентября 2009'),
            array('2009-10-01', '1 октября 2009'),
            array('1983-11-03', '3 ноября 1983'),
            array('2009-12-01', '1 декабря 2009'),
            array('1-труляля-2009', ''),
            array('fsdfsdfsd', ''),
            array('', ''),
            array(' ', ''),
        );
    }


    /**
     * @dataProvider providerBack
     * @param <type> $input
     * @param <type> $output
     */
    public function testBack($input, $output) {
        $filter = new Filter_Calendar_StringToDate();
        $this->assertEquals($filter->back($input), $output);
    }
}

