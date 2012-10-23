<?php
require_once realpath(dirname(__FILE__).'/../../../') . '/config/init.php';
require_once PATH_TESTS . '/init.php';
require_once PATH_LIBS_ZEND . '/Zend/View/Helper/Abstract.php';
require_once PATH_LIBS_ZEND. '/Zend/Db/Table/Abstract.php';
require_once PATH_LIBS . '/Mindfly/Outputs/TreeMenuOutput.php';
require_once PATH_LIBS . '/Mindfly/Date.php';



/**
 * Description of objectTableTest
 *
 * @author user
 */
class EventRowTest extends PHPUnit_Framework_TestCase {
    private $db;

    public function  setUp() {
        parent::setUp();
        $this->db = Zend_Registry::get('db');
    }

    public function testGetDates() {
        $categoryClass = new MfCalendarCategoryTable();
        $category1 = $categoryClass->create(TestTools::getRandomString(time()));
        $category2 = $categoryClass->create(TestTools::getRandomString(time()));
        $event11 = $category1->addEvent();
        $event12 = $category1->addEvent();
        $event21 = $category2->addEvent();
        $event22 = $category2->addEvent();
        $testDatesArray = array('1983-11-03', '1973-07-18',
                                '1983-11-03', '1973-11-03',
                                '1998-07-18', '1973-11-03',
                                '1998-02-18', '1973-10-03');
        $event11->addDate($testDatesArray[0]);
        $event11->addDate($testDatesArray[1]);
        $event12->addDate($testDatesArray[2]);
        $event12->addDate($testDatesArray[3]);
        $event21->addDate($testDatesArray[4]);
        $event21->addDate($testDatesArray[5]);
        $event22->addDate($testDatesArray[6]);
        $event22->addDate($testDatesArray[7]);
        foreach ($event21->getDates('1998') as $dateNet) {
            $dateActual[] = $dateNet->getDate();
        }
        $this->assertEquals(array('1998-07-18'), $dateActual);
    }

    /**
     * Тест получения даты с указанием года
     */
    public function testGetDatesWhithYear() {
        $categoryClass = new MfCalendarCategoryTable();
        $category = $categoryClass->create(TestTools::getRandomString(time()));
        $event = $category->addEvent();        
        $event->addDate('1981-01-04');
        $event->addDate('1982-11-04');
        $event->addDate('1981-05-04');
        $event->addDate('1982-12-14');
        $dateActual = array();
        foreach ($event->getDates('1981') as $dateNet) {
           $dateActual[] = $dateNet->getDate();
        }
        $this->assertEquals(array('1981-01-04','1981-05-04'), $dateActual);

        $dateActual = array();
        foreach ($event->getDates('1982') as $dateNet) {
           $dateActual[] = $dateNet->getDate();
        }
        $this->assertEquals(array('1982-11-04','1982-12-14'), $dateActual);


    }

        /**
     * тестируем добавление даты податно
     */
    public function testAddDate() {
         $categoryClass = new MfCalendarCategoryTable();
         $category = $categoryClass->create(TestTools::getRandomString(time()));
         $event = $category->addEvent();
         $testDatesArray = array('1983-11-03', '1988-07-18','1983-11-03');
         $event->addDate($testDatesArray[0])
               ->addDate($testDatesArray[1])
               ->addDate($testDatesArray[2])
               ->save();
         $expDatesArray = $this->db->fetchAll('select * from mf_calendar_net enet where
                              enet.event_id = ' . $event->id . ' order by full_date');
         $this->assertEquals(2, count($expDatesArray));
         $this->assertEquals($expDatesArray[0]['full_date'], $testDatesArray[0]);
         $this->assertEquals($expDatesArray[1]['full_date'], $testDatesArray[1]);
    }

    /**
     * тестируем добавление примечания в дату
     */
    public function testAddDateWithRemark() {
        $categoryClass = new MfCalendarCategoryTable();
        $category = $categoryClass->create(TestTools::getRandomString(time()));
        $event = $category->addEvent();
        $expected[] = array(
            'date' => '2011-01-02',
            'remark' => TestTools::getRandomString(time())
        );
        $expected[] = array(
            'date' => '2011-01-01',
            'remark' => TestTools::getRandomString(time())
        );
        $expected[] = array(
            'date' => '2011-03-01',
            'remark' => ''
        );
        $event->addDate($expected[0]['date'], $expected[0]['remark'])
              ->addDate($expected[1]['date'], $expected[1]['remark'])
              ->addDate($expected[2]['date'], $expected[2]['remark']);

        foreach ($event->getDates('2011') as $dateNet) {
            $dateActual[] = array(
                'date' => $dateNet->getDate(),
                'remark' => $dateNet->remark
            );
        }
       $this->assertEquals($expected, $dateActual);
    }

    /**
     * тестируем обновление заметки
     * при добавлении существуюещей даты же даты
     */
    public function testUpdateRemark() {
        $categoryClass = new MfCalendarCategoryTable();
        $category = $categoryClass->create(TestTools::getRandomString(time()));
        $event = $category->addEvent();
        $event->addDate($date = '2000-10-11',$remark = 'xxx');
        $dateCurrent = $event->getDates('2000')->current();
        $this->assertEquals($date, $dateCurrent->getDate());
        $this->assertEquals($remark, $dateCurrent->remark);
        $event->addDate($date = '2000-10-11',$remark = 'y"<'."'".'yy');
        $dates = $event->getDates('2000');
        $this->assertEquals(1, count($dates));
        $dateCurrent = $dates->current();
        $this->assertEquals($date, $dateCurrent->getDate());
        $this->assertEquals($remark, $dateCurrent->remark);
    }

    /**
     * тестируем удаление даты
     */
    public function testDeleteDate() {
        $categoryClass = new MfCalendarCategoryTable();
        $category1 = $categoryClass->create(TestTools::getRandomString(time()));
        $category2 = $categoryClass->create(TestTools::getRandomString(time()));
        $event11 = $category1->addEvent();
        $event12 = $category1->addEvent();
        $event21 = $category2->addEvent();
        $event22 = $category2->addEvent();
        $testDatesArray = array('1983-11-03', '1988-07-18',
                                '1983-11-03', '1973-11-03',
                                '1998-07-18', '1973-11-03',
                                '1998-02-18', '1973-10-03');
        $event11->addDate($testDatesArray[0]);
        $event11->addDate($testDatesArray[1]);
        $event12->addDate($testDatesArray[2]);
        $event12->addDate($testDatesArray[3]);
        $event21->addDate($testDatesArray[4]);
        $event21->addDate($testDatesArray[5]);
        $event22->addDate($testDatesArray[6]);
        $event22->addDate($testDatesArray[7]);

        $event21->deleteDate('1998-07-18');
        $dateActual = array();
        foreach ($event21->getDates('1973') as $dateNet) {
            $dateActual[] = $dateNet->getDate();
        }
        $this->assertEquals(array('1973-11-03'), $dateActual);

        $dateActual = array();
        foreach ($event21->getDates('1998') as $dateNet) {
            $dateActual[] = $dateNet->getDate();
        }
        $this->assertEquals(array(), $dateActual);
    }


    public function providerGetMonthDatesString() {
        return array(
            array(array('2010-02-08'),
                  '2010', '02', '8 февраля'),/*
            array(array('2010-02-08', '2010-02-09', '2010-02-10', '2010-02-11'),
                  '2010', '02', '8-11 февраля'),
            array(array('2010-02-02', '2010-02-03'),
                  '2010', '02', '2,3 февраля'),
            array(array('2010-01-31', '2010-02-01', '2010-02-02', '2010-02-03',
                        '2010-02-04', '2010-02-05', '2010-02-06', '2010-02-07',
                        '2010-02-08', '2010-02-09', '2010-02-10', '2010-02-11',
                        '2010-02-12', '2010-02-13', '2010-02-14', '2010-02-15',
                        '2010-02-16', '2010-02-17', '2010-02-18', '2010-02-19',
                        '2010-02-20', '2010-02-21', '2010-02-22', '2010-02-23',
                        '2010-02-24', '2010-02-25', '2010-02-26', '2010-02-27',
                        '2010-02-28','2010-03-01'),
                  '2010', '02', 'весь февраль'), 
            array(array('2010-02-02', '2010-02-03',
                        '2010-02-04', '2010-02-05', '2010-02-06', '2010-02-07',
                        '2010-02-08', '2010-02-09', '2010-02-10', '2010-02-11',
                        '2010-02-12', '2010-02-13', '2010-02-14', '2010-02-15',
                        '2010-02-16', '2010-02-17', '2010-02-18', '2010-02-19',
                        '2010-02-20', '2010-02-21', '2010-02-22', '2010-02-23',
                        '2010-02-24', '2010-02-25', '2010-02-26', '2010-02-27',
                        '2010-02-28','2010-03-01'),
                 '2010', '02', 'со 2 февраля'),
            array(array('2010-02-01', '2010-02-02', '2010-02-03',
                        '2010-02-04', '2010-02-05', '2010-02-06', '2010-02-07',
                        '2010-02-08', '2010-02-09', '2010-02-10', '2010-02-11',
                        '2010-02-12', '2010-02-13', '2010-02-14', '2010-02-15',
                        '2010-02-16', '2010-02-17', '2010-02-18', '2010-02-19',
                        '2010-02-20', '2010-02-21', '2010-02-22', '2010-02-23',
                        '2010-02-24', '2010-02-25', '2010-02-26', '2010-02-27',
                        '2010-02-28', '2010-03-01'),
                  '2010', '02', 'с 1 февраля'),
            array(array('2010-01-30', '2010-01-31', '2010-02-01', '2010-02-02'),
                  '2010','02', 'до 2 февраля'),
            array(array('2010-01-31', '2010-02-01', '2010-02-02', '2010-02-03',
                        '2010-02-04', '2010-02-05', '2010-02-06', '2010-02-07',
                        '2010-02-08', '2010-02-09', '2010-02-10', '2010-02-11',
                        '2010-02-12'),
                  '2010', '02', 'до 12 февраля'),
            array(array('2009-12-31', '2010-01-01', '2010-01-02', '2010-01-03'),
                  '2010', '01', 'до 3 января'),
            array(array('2010-12-29', '2010-12-30', '2010-12-31',
                        '2011-01-01', '2011-01-02'),
                        '2010', '12', 'с 29 декабря'),
            array(array('2011-04-25', '2011-04-26', '2011-04-27',
                        '2011-04-28', '2011-04-29', '2011-04-30'),
                        '2011', '04', '25-30 апреля'),
            array(array('2011-04-25', '2011-04-26', '2011-04-27',
                        '2011-04-28', '2011-04-29', '2011-04-30','2011-05-01'),
                        '2011', '04', 'с 25 апреля'),
            */
        );
    }

    /**
     * @dataProvider providerGetMonthDatesString
     */
    public function testGetMonthDatesString($dates, $year, $month, $expected) {
        $categoryClass = new MfCalendarCategoryTable();
        $category = $categoryClass->create(TestTools::getRandomString(time()));
        $event = $category->addEvent();
        foreach ($dates as $date) {
            $event->addDate($date);
        }
        $this->assertEquals($expected, $event->getMonthDatesString($year, $month));
    }


    public function testClear(){}
   
}