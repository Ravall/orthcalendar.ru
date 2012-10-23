<?php
require_once realpath(dirname(__FILE__).'/../../../') . '/config/init.php';
require_once PATH_TESTS . '/init.php';
require_once PATH_LIBS . '/Mindfly/Date.php';
/**
 * Тестирование модуля даты
 *
 * @author ravall
 */
class NetTest extends Tests_Lib_TestCaseSimple {
      
    public function setUp() {
        $this->markTestSkipped();
        $this->categoryOne = MfCalendarCategoryPeer::creatCategory(TestTools::getRandomString(time()));
        $this->categoryTwo = MfCalendarCategoryPeer::creatCategory(TestTools::getRandomString(time()));
        $this->categoryThree = MfCalendarCategoryPeer::creatCategory(TestTools::getRandomString(time()));

        $this->eventOne1 = $this->categoryOne->addEvent();
        // несколько удаленных событий в одной категории
        $this->eventOne2 = $this->categoryOne->addEvent();
        $this->eventOne3 = $this->categoryOne->addEvent()->getObject()->setDelete();

        // два события в другой
        $this->eventTwo1 = $this->categoryTwo->addEvent();
        $this->eventTwo2 = $this->categoryTwo->addEvent();
        // в третьей нет событий

        $arrayOfDatesEventOne1 = array('2010-10-01', '2010-10-15', '2010-10-31',
                                       '2010-11-01', '2010-12-30', '2010-09-15');
        $arrayOfDatesEventTwo1 = array('2010-10-11', '2010-10-01', '2010-09-01');
        $arrayOfDatesEventTwo2 = array('2010-10-01', '2010-10-15', '2010-09-01', '2010-09-02');

        $this->eventOne1->addDateArray($arrayOfDatesEventOne1);
        $this->eventTwo1->addDateArray($arrayOfDatesEventTwo1);
        $this->eventTwo2->addDateArray($arrayOfDatesEventTwo2);

        // немного событий в удаленную категорию для путанницы
        $arrayOfDeletedCategory = array('2010-10-01', '2010-09-15');
        $this->eventOne2->addDateArray($arrayOfDeletedCategory);
        $this->eventOne2->getObject()->setDelete();
    }


   

    public function testGetEventsMapMonth() {       
        $categoryes = array($this->categoryOne->getId(), $this->categoryTwo->getId());        
        $date = new Mindfly_Date('2010-10-01');
        $expectedMap = $date->getEmptyMonthMap();
        $expectedMap['2010-10-01'] = 3;
        $expectedMap['2010-10-11'] = 1;
        $expectedMap['2010-10-15'] = 2;
        $expectedMap['2010-10-31'] = 1;
        $map = MfCalendarNetPeer::getEventsMapMonth($categoryes, '10', '2010');        
        $this->assertEquals($expectedMap, $map, 'Ожидаемая карта событий на месяц не верна');


        $expectedMap = $date->getEmptyMonthMap();
        $expectedMap['2010-10-01'] = 1;
        $expectedMap['2010-10-15'] = 1;
        $expectedMap['2010-10-31'] = 1;
        $categoryes = array($this->categoryOne->getId());
        $map = MfCalendarNetPeer::getEventsMapMonth($categoryes, '10', '2010');
        $this->assertEquals($expectedMap, $map, 'Ожидаемая карта событий на месяц не верна');
    }

    /**
     * тестируем получение событий дня
     */
    public function testGetEvetnsDay() {
        $categoryes = array($this->categoryOne->getId(), $this->categoryTwo->getId());
        $events = MfCalendarNetPeer::getEvetnsDay($categoryes, '2010-10-01');
        $arrayOfEventsId = array($this->eventOne1->getId(), $this->eventTwo1->getId(), $this->eventTwo2->getId());
        foreach ($events as $event) {
            $eventsActual[] = $event->getMfCalendarEvent()->getId();
        }
        $this->assertEquals($arrayOfEventsId,$eventsActual);
    }

    /**
     * тестируем дублирование даты
     */
    public function testAddDayDublicate() {
        $this->eventOne1->addDate('2009-02-02');
        $this->eventOne1->addDate('2009-02-01');
        $this->eventOne1->addDate('2009-02-02');
        $this->eventOne1->save();
        $events = MfCalendarNetPeer::getEvetnsDay(
            array($this->categoryOne->getId()), '2009-02-02'
        );
        $this->assertEquals(1,count($events),'неверное количество событий дня');
        $events2 = MfCalendarNetPeer::getEvetnsDay(
            array($this->categoryOne->getId()), '2009-01-01'
        );
        $this->assertEquals(0, count($events2),'Не нулевое количество событий');
    }

   
   
}

?>
