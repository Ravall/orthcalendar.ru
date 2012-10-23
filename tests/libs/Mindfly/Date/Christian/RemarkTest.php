<?php
/*
 * Тест Ремарков
 */
require_once realpath(dirname(__FILE__).'/../../../../../') . '/config/init.php';
require_once dirname(__FILE__) . '/Fake/FakeConfigRemark.php';
require_once PATH_TESTS . '/init.php';
require_once PATH_LIBS . '/Mindfly/Date/Christian/Remark.php';
require_once PATH_LIBS . '/Mindfly/Date.php';

class Mindfly_Date_Christian_RemarkTest extends PHPUnit_Framework_TestCase {

    public static function setUpBeforeClass() {
                
        $rediska = Rediska_Manager::get(REDIS_NAMESPACE);
        $rediska->flushDb();
        $remark = new Mindfly_Date_Christian_Remark(new FakeConfigRemark());

        $map = array_merge($remark->getYearMap('2009'), $remark->getYearMap('2010'), $remark->getYearMap('2011'));
        
        $fastTable = new MfCalendarOrthodoxyFast();
        // добавляем карту ремарков
        foreach ($map as $params) {
            $fastTable->addDate($params['day'], $params['event_id'], $params['text'], $params['z-index']);
        }
    }

    public function setUp() {
        parent::setUp();
        $this->config = new FakeConfigRemark();        
    }

    public function  providerRemark() {
        $config = new FakeConfigRemark();
        return array(
            array('2010-01-01',  'xerophagy', $config->event->fast->advent),//5
            array('2010-01-02',  'comment5', $config->event->fast->advent),//6
            array('2010-01-03',  'comment5', $config->event->fast->advent),//7
            array('2010-01-04',  'xerophagy', $config->event->fast->advent),//1
            array('2010-01-05',  'comment2', $config->event->fast->advent),//2
            array('2010-01-06',  'sochivo', $config->event->other->theEveOfTheophany),//3
            array('2010-01-07',  'nofast', $config->semidniza->christmasWeek),//4
            array('2010-01-08',  'nofast', $config->semidniza->christmasWeek),//5
            array('2010-01-09',  'nofast', $config->semidniza->christmasWeek),//6
            array('2010-01-10',  'nofast', $config->semidniza->christmasWeek),//7
            array('2010-01-11',  'nofast', $config->semidniza->christmasWeek),//1
            array('2010-01-12',  'nofast', $config->semidniza->christmasWeek),//2
            array('2010-01-13',  'nofast', $config->semidniza->christmasWeek),//3
            array('2010-01-14',  'nofast', $config->semidniza->christmasWeek),//4
            array('2010-01-15',  'nofast', $config->semidniza->christmasWeek),//5
            array('2010-01-16',  'nofast', $config->semidniza->christmasWeek),//6
            array('2010-01-17',  'nofast', $config->semidniza->christmasWeek),//7
            array('2010-01-18',  'sochivo', $config->event->other->theEveOfEpiphany),//1  <-- здесь святки еще идут
            array('2010-01-19',  'nofast', $config->meatfare->winter),//2
            array('2010-01-20',  'comment1', $config->event->fastweek),//3
            array('2010-01-21',  'nofast', $config->meatfare->winter),//4
            array('2010-01-22',  'comment1', $config->event->fastweek),//5
            array('2010-01-23',  'nofast', $config->meatfare->winter),//6
            array('2010-01-24',  'nofast', $config->meatfare->winter),//7     <- неделя Неделя о мытаре и фарисее

            array('2010-01-25',  'nofast', $config->semidniza->taxCollectorAndPhariseeWeek),//1
            array('2010-01-26',  'nofast', $config->semidniza->taxCollectorAndPhariseeWeek),//2
            array('2010-01-27',  'nofast', $config->semidniza->taxCollectorAndPhariseeWeek),//3
            array('2010-01-28',  'nofast', $config->semidniza->taxCollectorAndPhariseeWeek),//4
            array('2010-01-29',  'nofast', $config->semidniza->taxCollectorAndPhariseeWeek),//5
            array('2010-01-30',  'nofast', $config->semidniza->taxCollectorAndPhariseeWeek),//6
            array('2010-01-31',  'nofast', $config->semidniza->taxCollectorAndPhariseeWeek),//7

            array('2010-02-01',  'nofast', $config->semidniza->sexagesima),//1
            array('2010-02-02',  'nofast', $config->semidniza->sexagesima),//2
            array('2010-02-03',  'comment1', $config->semidniza->sexagesima),//3
            array('2010-02-04',  'nofast', $config->semidniza->sexagesima),//4
            array('2010-02-05',  'comment1', $config->semidniza->sexagesima),//5
            array('2010-02-06',  'nofast', $config->semidniza->sexagesima),//6
            array('2010-02-07',  'nofast', $config->semidniza->sexagesima),//7

            array('2010-02-08',  'comment3', $config->semidniza->carnivalWeek),//1
            array('2010-02-09',  'comment3', $config->semidniza->carnivalWeek),//2
            array('2010-02-10',  'comment3', $config->semidniza->carnivalWeek),//3
            array('2010-02-11',  'comment3', $config->semidniza->carnivalWeek),//4
            array('2010-02-12',  'comment3', $config->semidniza->carnivalWeek),//5
            array('2010-02-13',  'comment3', $config->semidniza->carnivalWeek),//6
            array('2010-02-14',  'comment3', $config->semidniza->carnivalWeek),//7

            array('2010-02-15',  'xerophagy', $config->event->fast->lent),//1
            array('2010-02-16',  'xerophagy', $config->event->fast->lent),//2
            array('2010-02-17',  'xerophagy', $config->event->fast->lent),//3
            array('2010-02-18',  'xerophagy', $config->event->fast->lent),//4
            array('2010-02-19',  'xerophagy', $config->event->fast->lent),//5
            array('2010-02-20',  'xerophagy',  $config->event->fast->lent),//6
            array('2010-02-21',  'xerophagy', $config->event->fast->lent),//7 <- Торжество Православия

            array('2010-02-22',  'xerophagy', $config->event->fast->lent),//1
            array('2010-02-23',  'comment2', $config->event->fast->lent),//2
            array('2010-02-24',  'xerophagy', $config->event->fast->lent),//3
            array('2010-02-25',  'comment2', $config->event->fast->lent),//4
            array('2010-02-26',  'xerophagy', $config->event->fast->lent),//5
            array('2010-02-27',  'comment4', $config->event->fast->lent),//6
            array('2010-02-28',  'comment4', $config->event->fast->lent),//7  <- святителя Григория Паламы

            array('2010-03-01',  'xerophagy', $config->event->fast->lent),//1
            array('2010-03-02',  'comment2', $config->event->fast->lent),//2
            array('2010-03-03',  'xerophagy', $config->event->fast->lent),//3
            array('2010-03-04',  'comment2', $config->event->fast->lent),//4
            array('2010-03-05',  'xerophagy', $config->event->fast->lent),//5
            array('2010-03-06',  'comment4', $config->event->fast->lent),//6
            array('2010-03-07',  'comment4', $config->event->fast->lent),//7 <- Крестопоклонная неделя

            array('2010-03-08',  'xerophagy', $config->event->fast->lent),//1
            array('2010-03-09',  'comment2', $config->event->fast->lent),//2
            array('2010-03-10',  'xerophagy', $config->event->fast->lent),//3
            array('2010-03-11',  'comment2', $config->event->fast->lent),//4
            array('2010-03-12',  'xerophagy', $config->event->fast->lent),//5
            array('2010-03-13',  'comment4', $config->event->fast->lent),//6
            array('2010-03-14',  'comment4', $config->event->fast->lent),//7  <- преподобного Иоанна Лествичника

            array('2010-03-15',  'xerophagy', $config->event->fast->lent),//1
            array('2010-03-16',  'comment2', $config->event->fast->lent),//2
            array('2010-03-17',  'xerophagy', $config->event->fast->lent),//3
            array('2010-03-18',  'comment5', $config->fortyDayFast->stationsMaryTheEgyptian),//4
            array('2010-03-19',  'xerophagy', $config->event->fast->lent),//5
            array('2010-03-20',  'comment4', $config->event->fast->lent),//6
            array('2010-03-21',  'comment4', $config->event->fast->lent),//7 <- преподобной Марии Египетской

            array('2010-03-22',  'xerophagy', $config->event->fast->lent),//1
            array('2010-03-23',  'comment2', $config->event->fast->lent),//2
            array('2010-03-24',  'xerophagy', $config->event->fast->lent),//3
            array('2010-03-25',  'comment2', $config->event->fast->lent),//4
            array('2010-03-26',  'xerophagy', $config->event->fast->lent),//5
            array('2010-03-27',  'lazarus_saturday', $config->fortyDayFast->lazarusSaturday),//6 <-- лазарева суббота
            array('2010-03-28',  'comment3', $config->greatFeasts->theEntryOfOurLordIntoJerusalem),//7 <- Вход Господень в Иерусалим (Вербное воскресенье)

            array('2010-03-29',  'xerophagy', $config->passion->monday),//1
            array('2010-03-30',  'xerophagy', $config->passion->tuesday),//2
            array('2010-03-31',  'xerophagy', $config->passion->wednesday),//3 <-Великая Среда.
            array('2010-04-01',  'comment4', $config->passion->thursday),//4 <- Великий Четверток - Воспоминание Тайной Вечери  Вечером читаются 12 Евангелий
            array('2010-04-02',  'famine', $config->passion->friday), //5 <-  Великий Пяток - Воспоминание Святых Страстей Христовых Днем - вынос Плащаницы.       Вечером - Чин Погребения Спасителя.
            array('2010-04-03',  'comment2', $config->passion->saturday),//6 <-Великая Суббота.
            
            array('2010-04-04',  'nofast', $config->semidniza->eastertideWeek),//7
            array('2010-04-05',  'nofast', $config->semidniza->eastertideWeek),//1
            array('2010-04-06',  'nofast', $config->semidniza->eastertideWeek),//2
            array('2010-04-07',  'nofast', $config->semidniza->eastertideWeek),//3
            array('2010-04-08',  'nofast', $config->semidniza->eastertideWeek),//4
            array('2010-04-09',  'nofast', $config->semidniza->eastertideWeek),//5
            array('2010-04-10',  'nofast', $config->semidniza->eastertideWeek),//6
            array('2010-04-11',  'nofast', $config->meatfare->spring),//7
            array('2010-04-12',  'nofast', $config->meatfare->spring),//1
            array('2010-04-13',  'nofast', $config->meatfare->spring),//2
            array('2010-04-14',  'comment1', $config->event->fastweek),//3
            array('2010-04-15',  'nofast', $config->meatfare->spring),//4
            array('2010-04-16',  'comment1', $config->event->fastweek),//5
            array('2010-04-17',  'nofast', $config->meatfare->spring),//6
            array('2010-04-18',  'nofast', $config->meatfare->spring),//7
            array('2010-04-19',  'nofast', $config->meatfare->spring),//1
            array('2010-04-20',  'nofast', $config->meatfare->spring),//2
            array('2010-04-21',  'comment1', $config->event->fastweek),//3
            array('2010-04-22',  'nofast', $config->meatfare->spring),//4
            array('2010-04-23',  'comment1', $config->event->fastweek),//5
            array('2010-04-24',  'nofast', $config->meatfare->spring),//6
            array('2010-04-25',  'nofast', $config->meatfare->spring),//7
            array('2010-04-26',  'nofast', $config->meatfare->spring),//1
            array('2010-04-27',  'nofast', $config->meatfare->spring),//2
            array('2010-04-28',  'comment1', $config->event->fastweek),//3
            array('2010-04-29',  'nofast', $config->meatfare->spring),//4
            array('2010-04-30',  'comment1', $config->event->fastweek),//5
            array('2010-05-01',  'nofast', $config->meatfare->spring),//6
            array('2010-05-02',  'nofast', $config->meatfare->spring),//7
            array('2010-05-03',  'nofast', $config->meatfare->spring),//1
            array('2010-05-04',  'nofast', $config->meatfare->spring),//2
            array('2010-05-05',  'comment1', $config->event->fastweek),//3
            array('2010-05-06',  'nofast', $config->meatfare->spring),//4
            array('2010-05-07',  'comment1', $config->event->fastweek),//5
            array('2010-05-08',  'nofast', $config->meatfare->spring),//6
            array('2010-05-09',  'nofast', $config->meatfare->spring),//7
            array('2010-05-10',  'nofast', $config->meatfare->spring),//1
            array('2010-05-11',  'nofast', $config->meatfare->spring),//2
            array('2010-05-12',  'comment1', $config->event->fastweek),//3
            array('2010-05-13',  'nofast', $config->meatfare->spring),//4
            array('2010-05-14',  'comment1', $config->event->fastweek),//5
            array('2010-05-15',  'nofast', $config->meatfare->spring),//6
            array('2010-05-16',  'nofast', $config->meatfare->spring),//7
            array('2010-05-17',  'nofast', $config->meatfare->spring),//1
            array('2010-05-18',  'nofast', $config->meatfare->spring),//2
            array('2010-05-19',  'comment1', $config->event->fastweek),//3
            array('2010-05-20',  'nofast', $config->meatfare->spring),//4
            array('2010-05-21',  'comment1', $config->event->fastweek),//5
            array('2010-05-22',  'nofast', $config->meatfare->spring),//6
            array('2010-05-23',  'nofast', $config->meatfare->spring),//7
            array('2010-05-24',  'nofast', $config->semidniza->whitsunWeek),//1
            array('2010-05-25',  'nofast', $config->semidniza->whitsunWeek),//2
            array('2010-05-26',  'nofast', $config->semidniza->whitsunWeek),//3
            array('2010-05-27',  'nofast', $config->semidniza->whitsunWeek),//4
            array('2010-05-28',  'nofast', $config->semidniza->whitsunWeek),//5
            array('2010-05-29',  'nofast', $config->semidniza->whitsunWeek),//6
            array('2010-05-30',  'nofast', $config->semidniza->whitsunWeek),//7
            array('2010-05-31',  'xerophagy', $config->event->fast->apostels),//1
            array('2010-06-01',  'comment2', $config->event->fast->apostels),//2
            array('2010-06-02',  'xerophagy', $config->event->fast->apostels),//3
            array('2010-06-03',  'comment2', $config->event->fast->apostels),//4
            array('2010-06-04',  'xerophagy', $config->event->fast->apostels),//5
            array('2010-06-05',  'comment1', $config->event->fast->apostels),//6
            array('2010-06-06',  'comment1', $config->event->fast->apostels),//7
            array('2010-06-07',  'xerophagy', $config->event->fast->apostels),//1
            array('2010-06-08',  'comment2', $config->event->fast->apostels),//2
            array('2010-06-09',  'xerophagy', $config->event->fast->apostels),//3
            array('2010-06-10',  'comment2', $config->event->fast->apostels),//4
            array('2010-06-11',  'xerophagy', $config->event->fast->apostels),//5
            array('2010-06-12',  'comment1', $config->event->fast->apostels),//6
            array('2010-06-13',  'comment1', $config->event->fast->apostels),//7
            array('2010-06-14',  'xerophagy', $config->event->fast->apostels),//1
            array('2010-06-15',  'comment2', $config->event->fast->apostels),//2
            array('2010-06-16',  'xerophagy', $config->event->fast->apostels),//3
            array('2010-06-17',  'comment2', $config->event->fast->apostels),//4
            array('2010-06-18',  'xerophagy', $config->event->fast->apostels),//5
            array('2010-06-19',  'comment1', $config->event->fast->apostels),//6
            array('2010-06-20',  'comment1', $config->event->fast->apostels),//7
            array('2010-06-21',  'xerophagy', $config->event->fast->apostels),//1
            array('2010-06-22',  'comment2', $config->event->fast->apostels),//2
            array('2010-06-23',  'xerophagy', $config->event->fast->apostels),//3
            array('2010-06-24',  'comment2', $config->event->fast->apostels),//4
            array('2010-06-25',  'xerophagy', $config->event->fast->apostels),//5
            array('2010-06-26',  'comment1', $config->event->fast->apostels),//6
            array('2010-06-27',  'comment1', $config->event->fast->apostels),//7
            array('2010-06-28',  'xerophagy', $config->event->fast->apostels),//1
            array('2010-06-29',  'comment2', $config->event->fast->apostels),//2
            array('2010-06-30',  'xerophagy', $config->event->fast->apostels),//3
            array('2010-07-01',  'comment2', $config->event->fast->apostels),//4
            array('2010-07-02',  'xerophagy', $config->event->fast->apostels),//5
            array('2010-07-03',  'comment1', $config->event->fast->apostels),//6
            array('2010-07-04',  'comment1', $config->event->fast->apostels),//7
            array('2010-07-05',  'xerophagy', $config->event->fast->apostels),//1
            array('2010-07-06',  'comment2', $config->event->fast->apostels),//2
            array('2010-07-07',  'xerophagy', $config->event->fast->apostels),//3
            array('2010-07-08',  'comment2', $config->event->fast->apostels),//4
            array('2010-07-09',  'xerophagy', $config->event->fast->apostels),//5
            array('2010-07-10',  'comment1', $config->event->fast->apostels),//6
            array('2010-07-11',  'comment1', $config->event->fast->apostels),//7
            array('2010-07-12',  'nofast', $config->greatFeasts->peterAndPavelDay),//1
            array('2010-07-13',  'nofast', $config->meatfare->summer),//2
            array('2010-07-14',  'xerophagy', $config->event->fastweek),//3
            array('2010-07-15',  'nofast', $config->meatfare->summer),//4
            array('2010-07-16',  'xerophagy', $config->event->fastweek),//5
            array('2010-07-17',  'nofast', $config->meatfare->summer),//6
            array('2010-07-18',  'nofast', $config->meatfare->summer),//7
            array('2010-07-19',  'nofast', $config->meatfare->summer),//1
            array('2010-07-20',  'nofast', $config->meatfare->summer),//2
            array('2010-07-21',  'xerophagy', $config->event->fastweek),//3
            array('2010-07-22',  'nofast', $config->meatfare->summer),//4
            array('2010-07-23',  'xerophagy', $config->event->fastweek),//5
            array('2010-07-24',  'nofast', $config->meatfare->summer),//6
            array('2010-07-25',  'nofast', $config->meatfare->summer),//7
            array('2010-07-26',  'nofast', $config->meatfare->summer),//1
            array('2010-07-27',  'nofast', $config->meatfare->summer),//2
            array('2010-07-28',  'xerophagy', $config->event->fastweek),//3
            array('2010-07-29',  'nofast', $config->meatfare->summer),//4
            array('2010-07-30',  'xerophagy', $config->event->fastweek),//5
            array('2010-07-31',  'nofast', $config->meatfare->summer),//6
            array('2010-08-01',  'nofast', $config->meatfare->summer),//7
            array('2010-08-02',  'nofast', $config->meatfare->summer),//1
            array('2010-08-03',  'nofast', $config->meatfare->summer),//2
            array('2010-08-04',  'xerophagy', $config->event->fastweek),//3
            array('2010-08-05',  'nofast', $config->meatfare->summer),//4
            array('2010-08-06',  'xerophagy', $config->event->fastweek),//5
            array('2010-08-07',  'nofast', $config->meatfare->summer),//6
            array('2010-08-08',  'nofast', $config->meatfare->summer),//7
            array('2010-08-09',  'nofast', $config->meatfare->summer),//1
            array('2010-08-10',  'nofast', $config->meatfare->summer),//2
            array('2010-08-11',  'xerophagy', $config->event->fastweek),//3
            array('2010-08-12',  'nofast', $config->meatfare->summer),//4
            array('2010-08-13',  'xerophagy', $config->event->fastweek),//5
            array('2010-08-14',  'comment4', $config->event->fast->assumption),//6
            array('2010-08-15',  'comment4', $config->event->fast->assumption),//7
            array('2010-08-16',  'xerophagy', $config->event->fast->assumption),//1
            array('2010-08-17',  'comment2', $config->event->fast->assumption),//2
            array('2010-08-18',  'xerophagy', $config->event->fast->assumption),//3
            array('2010-08-19',  'comment2', $config->event->fast->assumption),//4
            array('2010-08-20',  'xerophagy', $config->event->fast->assumption),//5
            array('2010-08-21',  'comment4', $config->event->fast->assumption),//6
            array('2010-08-22',  'comment4', $config->event->fast->assumption),//7
            array('2010-08-23',  'xerophagy', $config->event->fast->assumption),//1
            array('2010-08-24',  'comment2', $config->event->fast->assumption),//2
            array('2010-08-25',  'xerophagy', $config->event->fast->assumption),//3
            array('2010-08-26',  'comment2', $config->event->fast->assumption),//4
            array('2010-08-27',  'xerophagy', $config->event->fast->assumption),//5
            array('2010-08-28',  'nofast', $config->greatFeasts->dormitionOfTheTheotokos),//6
            array('2010-08-29',  'nofast', $config->meatfare->autumn),//7
            array('2010-08-30',  'nofast', $config->meatfare->autumn),//1
            array('2010-08-31',  'nofast', $config->meatfare->autumn),//2
            array('2010-09-01',  'xerophagy', $config->event->fastweek),//3
            array('2010-09-02',  'nofast', $config->meatfare->autumn),//4
            array('2010-09-03',  'xerophagy', $config->event->fastweek),//5
            array('2010-09-04',  'nofast', $config->meatfare->autumn),//6
            array('2010-09-05',  'nofast', $config->meatfare->autumn),//7
            array('2010-09-06',  'nofast', $config->meatfare->autumn),//1
            array('2010-09-07',  'nofast', $config->meatfare->autumn),//2
            array('2010-09-08',  'xerophagy', $config->event->fastweek),//3
            array('2010-09-09',  'nofast', $config->meatfare->autumn),//4
            array('2010-09-10',  'xerophagy', $config->event->fastweek),//5
            array('2010-09-11',  'comment5',  $config->greatFeasts->beheadingOfStJohnTheBaptist),//6
            array('2010-09-12',  'nofast', $config->meatfare->autumn),//7
            array('2010-09-13',  'nofast', $config->meatfare->autumn),//1
            array('2010-09-14',  'nofast', $config->meatfare->autumn),//2
            array('2010-09-15',  'xerophagy', $config->event->fastweek),//3
            array('2010-09-16',  'nofast', $config->meatfare->autumn),//4
            array('2010-09-17',  'xerophagy', $config->event->fastweek),//5
            array('2010-09-18',  'nofast', $config->meatfare->autumn),//6
            array('2010-09-19',  'nofast', $config->meatfare->autumn),//7
            array('2010-09-20',  'nofast', $config->meatfare->autumn),//1
            array('2010-09-21',  'nofast', $config->meatfare->autumn),//2
            array('2010-09-22',  'xerophagy', $config->event->fastweek),//3
            array('2010-09-23',  'nofast', $config->meatfare->autumn),//4
            array('2010-09-24',  'xerophagy', $config->event->fastweek),//5
            array('2010-09-25',  'nofast', $config->meatfare->autumn),//6
            array('2010-09-26',  'nofast', $config->meatfare->autumn),//7
            array('2010-09-27',  'comment5',  $config->greatFeasts->exaltationOfTheCross),//1
            array('2010-09-28',  'nofast', $config->meatfare->autumn),//2
            array('2010-09-29',  'xerophagy', $config->event->fastweek),//3
            array('2010-09-30',  'nofast', $config->meatfare->autumn),//4
            array('2010-10-01',  'xerophagy', $config->event->fastweek),//5
            array('2010-10-02',  'nofast', $config->meatfare->autumn),//6
            array('2010-10-03',  'nofast', $config->meatfare->autumn),//7
            array('2010-10-04',  'nofast', $config->meatfare->autumn),//1
            array('2010-10-05',  'nofast', $config->meatfare->autumn),//2
            array('2010-10-06',  'xerophagy', $config->event->fastweek),//3
            array('2010-10-07',  'nofast', $config->meatfare->autumn),//4
            array('2010-10-08',  'xerophagy', $config->event->fastweek),//5
            array('2010-10-09',  'nofast', $config->meatfare->autumn),//6
            array('2010-10-10',  'nofast', $config->meatfare->autumn),//7
            array('2010-10-11',  'nofast', $config->meatfare->autumn),//1
            array('2010-10-12',  'nofast', $config->meatfare->autumn),//2
            array('2010-10-13',  'xerophagy', $config->event->fastweek),//3
            array('2010-10-14',  'nofast', $config->meatfare->autumn),//4
            array('2010-10-15',  'xerophagy', $config->event->fastweek),//5
            array('2010-10-16',  'nofast', $config->meatfare->autumn),//6
            array('2010-10-17',  'nofast', $config->meatfare->autumn),//7
            array('2010-10-18',  'nofast', $config->meatfare->autumn),//1
            array('2010-10-19',  'nofast', $config->meatfare->autumn),//2
            array('2010-10-20',  'xerophagy', $config->event->fastweek),//3
            array('2010-10-21',  'nofast', $config->meatfare->autumn),//4
            array('2010-10-22',  'xerophagy', $config->event->fastweek),//5
            array('2010-10-23',  'nofast', $config->meatfare->autumn),//6
            array('2010-10-24',  'nofast', $config->meatfare->autumn),//7
            array('2010-10-25',  'nofast', $config->meatfare->autumn),//1
            array('2010-10-26',  'nofast', $config->meatfare->autumn),//2
            array('2010-10-27',  'xerophagy', $config->event->fastweek),//3
            array('2010-10-28',  'nofast', $config->meatfare->autumn),//4
            array('2010-10-29',  'xerophagy', $config->event->fastweek),//5
            array('2010-10-30',  'nofast', $config->meatfare->autumn),//6
            array('2010-10-31',  'nofast', $config->meatfare->autumn),//7
            array('2010-11-01',  'nofast', $config->meatfare->autumn),//1
            array('2010-11-02',  'nofast', $config->meatfare->autumn),//2
            array('2010-11-03',  'xerophagy', $config->event->fastweek),//3
            array('2010-11-04',  'nofast', $config->meatfare->autumn),//4
            array('2010-11-05',  'xerophagy', $config->event->fastweek),//5
            array('2010-11-06',  'nofast', $config->meatfare->autumn),//6
            array('2010-11-07',  'nofast', $config->meatfare->autumn),//7
            array('2010-11-08',  'nofast', $config->meatfare->autumn),//1
            array('2010-11-09',  'nofast', $config->meatfare->autumn),//2
            array('2010-11-10',  'xerophagy', $config->event->fastweek),//3
            array('2010-11-11',  'nofast', $config->meatfare->autumn),//4
            array('2010-11-12',  'xerophagy', $config->event->fastweek),//5
            array('2010-11-13',  'nofast', $config->meatfare->autumn),//6
            array('2010-11-14',  'nofast', $config->meatfare->autumn),//7
            array('2010-11-15',  'nofast', $config->meatfare->autumn),//1
            array('2010-11-16',  'nofast', $config->meatfare->autumn),//2
            array('2010-11-17',  'xerophagy', $config->event->fastweek),//3
            array('2010-11-18',  'nofast', $config->meatfare->autumn),//4
            array('2010-11-19',  'xerophagy', $config->event->fastweek),//5
            array('2010-11-20',  'nofast', $config->meatfare->autumn),//6
            array('2010-11-21',  'nofast', $config->meatfare->autumn),//7
            array('2010-11-22',  'nofast', $config->meatfare->autumn),//1
            array('2010-11-23',  'nofast', $config->meatfare->autumn),//2
            array('2010-11-24',  'xerophagy', $config->event->fastweek),//3
            array('2010-11-25',  'nofast', $config->meatfare->autumn),//4
            array('2010-11-26',  'xerophagy', $config->event->fastweek),//5
            array('2010-11-27',  'nofast', $config->meatfare->autumn),//6
            array('2010-11-28',  'comment3', $config->event->fast->advent),//7
            array('2010-11-29',  'comment2', $config->event->fast->advent),//1
            array('2010-11-30',  'comment3', $config->event->fast->advent),//2
            array('2010-12-01',  'xerophagy', $config->event->fast->advent),//3
            array('2010-12-02',  'comment3', $config->event->fast->advent),//4
            array('2010-12-03',  'xerophagy', $config->event->fast->advent),//5
            array('2010-12-04',  'comment3', $config->greatFeasts->presentationOfTheTheotokos),//6
            array('2010-12-05',  'comment3', $config->event->fast->advent),//7
            array('2010-12-06',  'comment2', $config->event->fast->advent),//1
            array('2010-12-07',  'comment3', $config->event->fast->advent),//2
            array('2010-12-08',  'xerophagy', $config->event->fast->advent),//3
            array('2010-12-09',  'comment3', $config->event->fast->advent),//4
            array('2010-12-10',  'xerophagy', $config->event->fast->advent),//5
            array('2010-12-11',  'comment3', $config->event->fast->advent),//6
            array('2010-12-12',  'comment3', $config->event->fast->advent),//7
            array('2010-12-13',  'comment2', $config->event->fast->advent),//1
            array('2010-12-14',  'comment3', $config->event->fast->advent),//2
            array('2010-12-15',  'xerophagy', $config->event->fast->advent),//3
            array('2010-12-16',  'comment3', $config->event->fast->advent),//4
            array('2010-12-17',  'xerophagy', $config->event->fast->advent),//5
            array('2010-12-18',  'comment3', $config->event->fast->advent),//6
            array('2010-12-19',  'comment3', $config->event->fast->advent),//7
            array('2010-12-20',  'comment2', $config->event->fast->advent),//1
            array('2010-12-21',  'comment4', $config->event->fast->advent),//2
            array('2010-12-22',  'xerophagy', $config->event->fast->advent),//3
            array('2010-12-23',  'comment4', $config->event->fast->advent),//4
            array('2010-12-24',  'xerophagy', $config->event->fast->advent),//5
            array('2010-12-25',  'comment3', $config->event->fast->advent),//6
            array('2010-12-26',  'comment3', $config->event->fast->advent),//7
            array('2010-12-27',  'comment2', $config->event->fast->advent),//1
            array('2010-12-28',  'comment4', $config->event->fast->advent),//2
            array('2010-12-29',  'xerophagy', $config->event->fast->advent),//3
            array('2010-12-30',  'comment4', $config->event->fast->advent),//4
            array('2010-12-31',  'xerophagy', $config->event->fast->advent),//5
            
            array('2011-01-01',  'comment3', $config->event->fast->advent),//6
            array('2011-01-02',  'comment5', $config->event->fast->advent),//7
            array('2011-01-03',  'xerophagy', $config->event->fast->advent),//1
            array('2011-01-04',  'comment2', $config->event->fast->advent),//2
            array('2011-01-05',  'xerophagy', $config->event->fast->advent),//3
            array('2011-01-06',  'sochivo', $config->event->other->theEveOfTheophany),//4

            array('2011-01-07',  'nofast', $config->semidniza->christmasWeek),//5
            array('2011-01-08',  'nofast', $config->semidniza->christmasWeek),//6
            array('2011-01-09',  'nofast', $config->semidniza->christmasWeek),//7
            array('2011-01-10',  'nofast', $config->semidniza->christmasWeek),//1
            array('2011-01-11',  'nofast', $config->semidniza->christmasWeek),//2
            array('2011-01-12',  'nofast', $config->semidniza->christmasWeek),//3
            array('2011-01-13',  'nofast', $config->semidniza->christmasWeek),//4
            array('2011-01-14',  'nofast', $config->semidniza->christmasWeek),//5
            array('2011-01-15',  'nofast', $config->semidniza->christmasWeek),//6
            array('2011-01-16',  'nofast', $config->semidniza->christmasWeek),//7
            array('2011-01-17',  'nofast', $config->semidniza->christmasWeek),//1
            array('2011-01-18',  'sochivo', $config->event->other->theEveOfEpiphany),//2
            array('2011-01-19',  'comment1', $config->event->fastweek),//
            array('2011-01-20',  'nofast', $config->meatfare->winter),//4
            array('2011-01-21',  'comment1', $config->event->fastweek),//
            array('2011-01-22',  'nofast', $config->meatfare->winter),//6
            array('2011-01-23',  'nofast', $config->meatfare->winter),//7
            array('2011-01-24',  'nofast', $config->meatfare->winter),//1
            array('2011-01-25',  'nofast', $config->meatfare->winter),//2
            array('2011-01-26',  'comment1', $config->event->fastweek),//
            array('2011-01-27',  'nofast', $config->meatfare->winter),//4
            array('2011-01-28',  'comment1', $config->event->fastweek),//
            array('2011-01-29',  'nofast', $config->meatfare->winter),//6
            array('2011-01-30',  'nofast', $config->meatfare->winter),//7
            array('2011-01-31',  'nofast', $config->meatfare->winter),//1
            array('2011-02-01',  'nofast', $config->meatfare->winter),//2
            array('2011-02-02',  'comment1', $config->event->fastweek),//
            array('2011-02-03',  'nofast', $config->meatfare->winter),//4
            array('2011-02-04',  'comment1', $config->event->fastweek),//
            array('2011-02-05',  'nofast', $config->meatfare->winter),//6
            array('2011-02-06',  'nofast', $config->meatfare->winter),//7
            array('2011-02-07',  'nofast', $config->meatfare->winter),//1
            array('2011-02-08',  'nofast', $config->meatfare->winter),//2
            array('2011-02-09',  'comment1', $config->event->fastweek),//
            array('2011-02-10',  'nofast', $config->meatfare->winter),//4
            array('2011-02-11',  'comment1', $config->event->fastweek),//
            array('2011-02-12',  'nofast', $config->meatfare->winter),//6
            array('2011-02-13',  'nofast', $config->meatfare->winter),//7
            array('2011-02-14',  'nofast', $config->semidniza->taxCollectorAndPhariseeWeek),//1
            array('2011-02-15',  'nofast', $config->semidniza->taxCollectorAndPhariseeWeek),//2
            array('2011-02-16',  'nofast', $config->semidniza->taxCollectorAndPhariseeWeek),//3
            array('2011-02-17',  'nofast', $config->semidniza->taxCollectorAndPhariseeWeek),//4
            array('2011-02-18',  'nofast', $config->semidniza->taxCollectorAndPhariseeWeek),//5
            array('2011-02-19',  'nofast', $config->semidniza->taxCollectorAndPhariseeWeek),//6
            array('2011-02-20',  'nofast', $config->semidniza->taxCollectorAndPhariseeWeek),//7
            array('2011-02-21',  'nofast', $config->semidniza->sexagesima),//1
            array('2011-02-22',  'nofast', $config->semidniza->sexagesima),//2
            array('2011-02-23',  'comment1', $config->semidniza->sexagesima),//
            array('2011-02-24',  'nofast', $config->semidniza->sexagesima),//4
            array('2011-02-25',  'comment1', $config->semidniza->sexagesima),//
            array('2011-02-26',  'nofast', $config->semidniza->sexagesima),//6
            array('2011-02-27',  'nofast', $config->semidniza->sexagesima),//7
            array('2011-02-28',  'comment3', $config->semidniza->carnivalWeek),//1
            array('2011-03-01',  'comment3', $config->semidniza->carnivalWeek),//2
            array('2011-03-02',  'comment3', $config->semidniza->carnivalWeek),//3
            array('2011-03-03',  'comment3', $config->semidniza->carnivalWeek),//4
            array('2011-03-04',  'comment3', $config->semidniza->carnivalWeek),//5
            array('2011-03-05',  'comment3', $config->semidniza->carnivalWeek),//6
            array('2011-03-06',  'comment3', $config->semidniza->carnivalWeek),//7

            array('2011-03-07',  'xerophagy', $config->event->fast->lent),//1
            array('2011-03-08',  'xerophagy', $config->event->fast->lent),//2
            array('2011-03-09',  'xerophagy', $config->event->fast->lent),//3
            array('2011-03-10',  'xerophagy', $config->event->fast->lent),//4
            array('2011-03-11',  'xerophagy', $config->event->fast->lent),//5
            array('2011-03-12',  'xerophagy', $config->event->fast->lent),//6
            array('2011-03-13',  'xerophagy', $config->event->fast->lent),//7

            array('2011-03-14',  'xerophagy', $config->event->fast->lent),//1
            array('2011-03-15',  'comment2', $config->event->fast->lent),//2
            array('2011-03-16',  'xerophagy', $config->event->fast->lent),//3
            array('2011-03-17',  'comment2', $config->event->fast->lent),//4
            array('2011-03-18',  'xerophagy', $config->event->fast->lent),//5
            array('2011-03-19',  'comment4', $config->event->fast->lent),//6
            array('2011-03-20',  'comment4', $config->event->fast->lent),//7

            array('2011-03-21',  'xerophagy', $config->event->fast->lent),//1
            array('2011-03-22',  'comment2', $config->event->fast->lent),//2
            array('2011-03-23',  'xerophagy', $config->event->fast->lent),//3
            array('2011-03-24',  'comment2', $config->event->fast->lent),//4
            array('2011-03-25',  'xerophagy', $config->event->fast->lent),//5
            array('2011-03-26',  'comment4', $config->event->fast->lent),//6
            array('2011-03-27',  'comment4', $config->event->fast->lent),//7

            array('2011-03-28',  'xerophagy', $config->event->fast->lent),//1
            array('2011-03-29',  'comment2', $config->event->fast->lent),//2
            array('2011-03-30',  'xerophagy', $config->event->fast->lent),//3
            array('2011-03-31',  'comment2', $config->event->fast->lent),//4
            array('2011-04-01',  'xerophagy', $config->event->fast->lent),//5
            array('2011-04-02',  'comment4', $config->event->fast->lent),//6
            array('2011-04-03',  'comment4', $config->event->fast->lent),//7

            array('2011-04-04',  'xerophagy', $config->event->fast->lent),//1
            array('2011-04-05',  'comment2', $config->event->fast->lent),//2
            array('2011-04-06',  'xerophagy', $config->event->fast->lent),//3
            array('2011-04-07',  'comment5', $config->fortyDayFast->stationsMaryTheEgyptian),//4
            array('2011-04-08',  'xerophagy', $config->event->fast->lent),//5
            array('2011-04-09',  'comment4', $config->event->fast->lent),//6
            array('2011-04-10',  'comment4', $config->event->fast->lent),//7

            array('2011-04-11',  'xerophagy', $config->event->fast->lent),//1
            array('2011-04-12',  'comment2', $config->event->fast->lent),//2
            array('2011-04-13',  'xerophagy', $config->event->fast->lent),//3
            array('2011-04-14',  'comment2', $config->event->fast->lent),//4
            array('2011-04-15',  'xerophagy', $config->event->fast->lent),//5
            array('2011-04-16',  'lazarus_saturday', $config->fortyDayFast->lazarusSaturday),//6
            array('2011-04-17',  'comment3', $config->greatFeasts->theEntryOfOurLordIntoJerusalem),//7 <- Вход Господень в Иерусалим (Вербное воскресенье)

            array('2011-04-18',  'xerophagy', $config->passion->monday),//1
            array('2011-04-19',  'xerophagy', $config->passion->tuesday),//2
            array('2011-04-20',  'xerophagy', $config->passion->wednesday),//3
            array('2011-04-21',  'comment4', $config->passion->thursday),//4
            array('2011-04-22',  'famine', $config->passion->friday),//5
            array('2011-04-23',  'comment2', $config->passion->saturday),//6

            array('2011-04-24',  'nofast', $config->semidniza->eastertideWeek),//7
            array('2011-04-25',  'nofast', $config->semidniza->eastertideWeek),//1
            array('2011-04-26',  'nofast', $config->semidniza->eastertideWeek),//2
            array('2011-04-27',  'nofast', $config->semidniza->eastertideWeek),//3
            array('2011-04-28',  'nofast', $config->semidniza->eastertideWeek),//4
            array('2011-04-29',  'nofast', $config->semidniza->eastertideWeek),//5
            array('2011-04-30',  'nofast', $config->semidniza->eastertideWeek),//6

            array('2011-05-01',  'nofast', $config->meatfare->spring),//7
            array('2011-05-02',  'nofast', $config->meatfare->spring),//1
            array('2011-05-03',  'nofast', $config->meatfare->spring),//2
            array('2011-05-04',  'comment1', $config->event->fastweek),//3
            array('2011-05-05',  'nofast', $config->meatfare->spring),//4
            array('2011-05-06',  'comment1', $config->event->fastweek),//5
            array('2011-05-07',  'nofast', $config->meatfare->spring),//6
            array('2011-05-08',  'nofast', $config->meatfare->spring),//7
            array('2011-05-09',  'nofast', $config->meatfare->spring),//1
            array('2011-05-10',  'nofast', $config->meatfare->spring),//2
            array('2011-05-11',  'comment1', $config->event->fastweek),//3
            array('2011-05-12',  'nofast', $config->meatfare->spring),//4
            array('2011-05-13',  'comment1', $config->event->fastweek),//5
            array('2011-05-14',  'nofast', $config->meatfare->spring),//6
            array('2011-05-15',  'nofast', $config->meatfare->spring),//7
            array('2011-05-16',  'nofast', $config->meatfare->spring),//1
            array('2011-05-17',  'nofast', $config->meatfare->spring),//2
            array('2011-05-18',  'comment1', $config->event->fastweek),//3
            array('2011-05-19',  'nofast', $config->meatfare->spring),//4
            array('2011-05-20',  'comment1', $config->event->fastweek),//5
            array('2011-05-21',  'nofast', $config->meatfare->spring),//6
            array('2011-05-22',  'nofast', $config->meatfare->spring),//7
            array('2011-05-23',  'nofast', $config->meatfare->spring),//1
            array('2011-05-24',  'nofast', $config->meatfare->spring),//2
            array('2011-05-25',  'comment1', $config->event->fastweek),//3
            array('2011-05-26',  'nofast', $config->meatfare->spring),//4
            array('2011-05-27',  'comment1', $config->event->fastweek),//5
            array('2011-05-28',  'nofast', $config->meatfare->spring),//6
            array('2011-05-29',  'nofast', $config->meatfare->spring),//7
            array('2011-05-30',  'nofast', $config->meatfare->spring),//1
            array('2011-05-31',  'nofast', $config->meatfare->spring),//2
            array('2011-06-01',  'comment1', $config->event->fastweek),//3
            array('2011-06-02',  'nofast', $config->meatfare->spring),//4
            array('2011-06-03',  'comment1', $config->event->fastweek),//5
            array('2011-06-04',  'nofast', $config->meatfare->spring),//6
            array('2011-06-05',  'nofast', $config->meatfare->spring),//7
            array('2011-06-06',  'nofast', $config->meatfare->spring),//1
            array('2011-06-07',  'nofast', $config->meatfare->spring),//2
            array('2011-06-08',  'comment1', $config->event->fastweek),//3
            array('2011-06-09',  'nofast', $config->meatfare->spring),//4
            array('2011-06-10',  'comment1', $config->event->fastweek),//5
            array('2011-06-11',  'nofast', $config->meatfare->spring),//6
            array('2011-06-12',  'nofast', $config->meatfare->spring),//7
            array('2011-06-13',  'nofast', $config->semidniza->whitsunWeek),//1
            array('2011-06-14',  'nofast', $config->semidniza->whitsunWeek),//2
            array('2011-06-15',  'nofast', $config->semidniza->whitsunWeek),//3
            array('2011-06-16',  'nofast', $config->semidniza->whitsunWeek),//4
            array('2011-06-17',  'nofast', $config->semidniza->whitsunWeek),//5
            array('2011-06-18',  'nofast', $config->semidniza->whitsunWeek),//6
            array('2011-06-19',  'nofast', $config->semidniza->whitsunWeek),//7
            array('2011-06-20',  'xerophagy', $config->event->fast->apostels),//1
            array('2011-06-21',  'comment2', $config->event->fast->apostels),//2
            array('2011-06-22',  'xerophagy', $config->event->fast->apostels),//3
            array('2011-06-23',  'comment2', $config->event->fast->apostels),//4
            array('2011-06-24',  'xerophagy', $config->event->fast->apostels),//5
            array('2011-06-25',  'comment1', $config->event->fast->apostels),//6
            array('2011-06-26',  'comment1', $config->event->fast->apostels),//7
            array('2011-06-27',  'xerophagy', $config->event->fast->apostels),//1
            array('2011-06-28',  'comment2', $config->event->fast->apostels),//2
            array('2011-06-29',  'xerophagy', $config->event->fast->apostels),//3
            array('2011-06-30',  'comment2', $config->event->fast->apostels),//4
            array('2011-07-01',  'xerophagy', $config->event->fast->apostels),//5
            array('2011-07-02',  'comment1', $config->event->fast->apostels),//6
            array('2011-07-03',  'comment1', $config->event->fast->apostels),//7
            array('2011-07-04',  'xerophagy', $config->event->fast->apostels),//1
            array('2011-07-05',  'comment2', $config->event->fast->apostels),//2
            array('2011-07-06',  'xerophagy', $config->event->fast->apostels),//3
            array('2011-07-07',  'comment2', $config->event->fast->apostels),//4
            array('2011-07-08',  'xerophagy', $config->event->fast->apostels),//5
            array('2011-07-09',  'comment1', $config->event->fast->apostels),//6
            array('2011-07-10',  'comment1', $config->event->fast->apostels),//7
            array('2011-07-11',  'xerophagy', $config->event->fast->apostels),//1
            array('2011-07-12',  'nofast', $config->greatFeasts->peterAndPavelDay),//2
            array('2011-07-13',  'xerophagy', $config->event->fastweek),//3
            array('2011-07-14',  'nofast', $config->meatfare->summer),//4
            array('2011-07-15',  'xerophagy', $config->event->fastweek),//5
            array('2011-07-16',  'nofast', $config->meatfare->summer),//6
            array('2011-07-17',  'nofast', $config->meatfare->summer),//7
            array('2011-07-18',  'nofast', $config->meatfare->summer),//1
            array('2011-07-19',  'nofast', $config->meatfare->summer),//2
            array('2011-07-20',  'xerophagy', $config->event->fastweek),//3
            array('2011-07-21',  'nofast', $config->meatfare->summer),//4
            array('2011-07-22',  'xerophagy', $config->event->fastweek),//5
            array('2011-07-23',  'nofast', $config->meatfare->summer),//6
            array('2011-07-24',  'nofast', $config->meatfare->summer),//7
            array('2011-07-25',  'nofast', $config->meatfare->summer),//1
            array('2011-07-26',  'nofast', $config->meatfare->summer),//2
            array('2011-07-27',  'xerophagy', $config->event->fastweek),//3
            array('2011-07-28',  'nofast', $config->meatfare->summer),//4
            array('2011-07-29',  'xerophagy', $config->event->fastweek),//5
            array('2011-07-30',  'nofast', $config->meatfare->summer),//6
            array('2011-07-31',  'nofast', $config->meatfare->summer),//7
            array('2011-08-01',  'nofast', $config->meatfare->summer),//1
            array('2011-08-02',  'nofast', $config->meatfare->summer),//2
            array('2011-08-03',  'xerophagy', $config->event->fastweek),//3
            array('2011-08-04',  'nofast', $config->meatfare->summer),//4
            array('2011-08-05',  'xerophagy', $config->event->fastweek),//5
            array('2011-08-06',  'nofast', $config->meatfare->summer),//6
            array('2011-08-07',  'nofast', $config->meatfare->summer),//7
            array('2011-08-08',  'nofast', $config->meatfare->summer),//1
            array('2011-08-09',  'nofast', $config->meatfare->summer),//2
            array('2011-08-10',  'xerophagy', $config->event->fastweek),//3
            array('2011-08-11',  'nofast', $config->meatfare->summer),//4
            array('2011-08-12',  'xerophagy', $config->event->fastweek),//5
            array('2011-08-13',  'nofast', $config->meatfare->summer),//6
            array('2011-08-14',  'comment4', $config->event->fast->assumption),//7
            array('2011-08-15',  'xerophagy', $config->event->fast->assumption),//1
            array('2011-08-16',  'comment2', $config->event->fast->assumption),//2
            array('2011-08-17',  'xerophagy', $config->event->fast->assumption),//3
            array('2011-08-18',  'comment2', $config->event->fast->assumption),//4
            array('2011-08-19',  'xerophagy', $config->event->fast->assumption),//5
            array('2011-08-20',  'comment4', $config->event->fast->assumption),//6
            array('2011-08-21',  'comment4', $config->event->fast->assumption),//7
            array('2011-08-22',  'xerophagy', $config->event->fast->assumption),//1
            array('2011-08-23',  'comment2', $config->event->fast->assumption),//2
            array('2011-08-24',  'xerophagy', $config->event->fast->assumption),//3
            array('2011-08-25',  'comment2', $config->event->fast->assumption),//4
            array('2011-08-26',  'xerophagy', $config->event->fast->assumption),//5
            array('2011-08-27',  'comment4', $config->event->fast->assumption),//6
            array('2011-08-28',  'nofast', $config->greatFeasts->dormitionOfTheTheotokos),//7
            array('2011-08-29',  'nofast', $config->meatfare->autumn),//1
            array('2011-08-30',  'nofast', $config->meatfare->autumn),//2            
            array('2011-08-31',  'xerophagy', $config->event->fastweek),//3
            array('2011-09-01',  'nofast', $config->meatfare->autumn),//4
            array('2011-09-02',  'xerophagy', $config->event->fastweek),//5
            array('2011-09-03',  'nofast', $config->meatfare->autumn),//6
            array('2011-09-04',  'nofast', $config->meatfare->autumn),//7
            array('2011-09-05',  'nofast', $config->meatfare->autumn),//1
            array('2011-09-06',  'nofast', $config->meatfare->autumn),//2
            array('2011-09-07',  'xerophagy', $config->event->fastweek),//3
            array('2011-09-08',  'nofast', $config->meatfare->autumn),//4
            array('2011-09-09',  'xerophagy', $config->event->fastweek),//5
            array('2011-09-10',  'nofast',  $config->meatfare->autumn),//6
            array('2011-09-11',  'comment5', $config->greatFeasts->beheadingOfStJohnTheBaptist),//7
            array('2011-09-12',  'nofast', $config->meatfare->autumn),//1
            array('2011-09-13',  'nofast', $config->meatfare->autumn),//2
            array('2011-09-14',  'xerophagy', $config->event->fastweek),//3
            array('2011-09-15',  'nofast', $config->meatfare->autumn),//4
            array('2011-09-16',  'xerophagy', $config->event->fastweek),//5
            array('2011-09-17',  'nofast', $config->meatfare->autumn),//6
            array('2011-09-18',  'nofast', $config->meatfare->autumn),//7
            array('2011-09-19',  'nofast', $config->meatfare->autumn),//1
            array('2011-09-20',  'nofast', $config->meatfare->autumn),//2
            array('2011-09-21',  'xerophagy', $config->event->fastweek),//3
            array('2011-09-22',  'nofast', $config->meatfare->autumn),//4
            array('2011-09-23',  'xerophagy', $config->event->fastweek),//5
            array('2011-09-24',  'nofast', $config->meatfare->autumn),//6
            array('2011-09-25',  'nofast', $config->meatfare->autumn),//7
            array('2011-09-26',  'nofast', $config->meatfare->autumn),//1
            array('2011-09-27',  'comment5',  $config->greatFeasts->exaltationOfTheCross),//2
            array('2011-09-28',  'xerophagy', $config->event->fastweek),//3
            array('2011-09-29',  'nofast', $config->meatfare->autumn),//4
            array('2011-09-30',  'xerophagy', $config->event->fastweek),//5
            array('2011-10-01',  'nofast', $config->meatfare->autumn),//6
            array('2011-10-02',  'nofast', $config->meatfare->autumn),//7
            array('2011-10-03',  'nofast', $config->meatfare->autumn),//1
            array('2011-10-04',  'nofast', $config->meatfare->autumn),//2
            array('2011-10-05',  'xerophagy', $config->event->fastweek),//3
            array('2011-10-06',  'nofast', $config->meatfare->autumn),//4
            array('2011-10-07',  'xerophagy', $config->event->fastweek),//5
            array('2011-10-08',  'nofast', $config->meatfare->autumn),//6
            array('2011-10-09',  'nofast', $config->meatfare->autumn),//7
            array('2011-10-10',  'nofast', $config->meatfare->autumn),//1
            array('2011-10-11',  'nofast', $config->meatfare->autumn),//2
            array('2011-10-12',  'xerophagy', $config->event->fastweek),//3
            array('2011-10-13',  'nofast', $config->meatfare->autumn),//4
            array('2011-10-14',  'xerophagy', $config->event->fastweek),//5
            array('2011-10-15',  'nofast', $config->meatfare->autumn),//6
            array('2011-10-16',  'nofast', $config->meatfare->autumn),//7
            array('2011-10-17',  'nofast', $config->meatfare->autumn),//1
            array('2011-10-18',  'nofast', $config->meatfare->autumn),//2
            array('2011-10-19',  'xerophagy', $config->event->fastweek),//3
            array('2011-10-20',  'nofast', $config->meatfare->autumn),//4
            array('2011-10-21',  'xerophagy', $config->event->fastweek),//5
            array('2011-10-22',  'nofast', $config->meatfare->autumn),//6
            array('2011-10-23',  'nofast', $config->meatfare->autumn),//7
            array('2011-10-24',  'nofast', $config->meatfare->autumn),//1
            array('2011-10-25',  'nofast', $config->meatfare->autumn),//2
            array('2011-10-26',  'xerophagy', $config->event->fastweek),//3
            array('2011-10-27',  'nofast', $config->meatfare->autumn),//4
            array('2011-10-28',  'xerophagy', $config->event->fastweek),//5
            array('2011-10-29',  'nofast', $config->meatfare->autumn),//6
            array('2011-10-30',  'nofast', $config->meatfare->autumn),//7
            array('2011-10-31',  'nofast', $config->meatfare->autumn),//1
            array('2011-11-01',  'nofast', $config->meatfare->autumn),//2
            array('2011-11-02',  'xerophagy', $config->event->fastweek),//3
            array('2011-11-03',  'nofast', $config->meatfare->autumn),//4
            array('2011-11-04',  'xerophagy', $config->event->fastweek),//5
            array('2011-11-05',  'nofast', $config->meatfare->autumn),//6
            array('2011-11-06',  'nofast', $config->meatfare->autumn),//7
            array('2011-11-07',  'nofast', $config->meatfare->autumn),//1
            array('2011-11-08',  'nofast', $config->meatfare->autumn),//2
            array('2011-11-09',  'xerophagy', $config->event->fastweek),//3
            array('2011-11-10',  'nofast', $config->meatfare->autumn),//4
            array('2011-11-11',  'xerophagy', $config->event->fastweek),//5
            array('2011-11-12',  'nofast', $config->meatfare->autumn),//6
            array('2011-11-13',  'nofast', $config->meatfare->autumn),//7
            array('2011-11-14',  'nofast', $config->meatfare->autumn),//1
            array('2011-11-15',  'nofast', $config->meatfare->autumn),//2
            array('2011-11-16',  'xerophagy', $config->event->fastweek),//3
            array('2011-11-17',  'nofast', $config->meatfare->autumn),//4
            array('2011-11-18',  'xerophagy', $config->event->fastweek),//5
            array('2011-11-19',  'nofast', $config->meatfare->autumn),//6
            array('2011-11-20',  'nofast', $config->meatfare->autumn),//7
            array('2011-11-21',  'nofast', $config->meatfare->autumn),//1
            array('2011-11-22',  'nofast', $config->meatfare->autumn),//2
            array('2011-11-23',  'xerophagy', $config->event->fastweek),//3
            array('2011-11-24',  'nofast', $config->meatfare->autumn),//4
            array('2011-11-25',  'xerophagy', $config->event->fastweek),//5
            array('2011-11-26',  'nofast', $config->meatfare->autumn),//6
            array('2011-11-27',  'nofast', $config->meatfare->autumn),//7
            array('2011-11-28',  'comment2', $config->event->fast->advent),//1
            array('2011-11-29',  'comment3', $config->event->fast->advent),//2
            array('2011-11-30',  'xerophagy', $config->event->fast->advent),//3
            array('2011-12-01',  'comment3', $config->event->fast->advent),//4
            array('2011-12-02',  'xerophagy', $config->event->fast->advent),//5
            array('2011-12-03',  'comment3', $config->event->fast->advent),//6
            array('2011-12-04',  'comment3', $config->greatFeasts->presentationOfTheTheotokos),//7
            array('2011-12-05',  'comment2', $config->event->fast->advent),//1
            array('2011-12-06',  'comment3', $config->event->fast->advent),//2
            array('2011-12-07',  'xerophagy', $config->event->fast->advent),//3
            array('2011-12-08',  'comment3', $config->event->fast->advent),//4
            array('2011-12-09',  'xerophagy', $config->event->fast->advent),//5
            array('2011-12-10',  'comment3', $config->event->fast->advent),//6
            array('2011-12-11',  'comment3', $config->event->fast->advent),//7
            array('2011-12-12',  'comment2', $config->event->fast->advent),//1
            array('2011-12-13',  'comment3', $config->event->fast->advent),//2
            array('2011-12-14',  'xerophagy', $config->event->fast->advent),//3
            array('2011-12-15',  'comment3', $config->event->fast->advent),//4
            array('2011-12-16',  'xerophagy', $config->event->fast->advent),//5
            array('2011-12-17',  'comment3', $config->event->fast->advent),//6
            array('2011-12-18',  'comment3', $config->event->fast->advent),//7
            array('2011-12-19',  'comment2', $config->event->fast->advent),//1
            array('2011-12-20',  'comment4', $config->event->fast->advent),//2
            array('2011-12-21',  'xerophagy', $config->event->fast->advent),//3
            array('2011-12-22',  'comment4', $config->event->fast->advent),//4
            array('2011-12-23',  'xerophagy', $config->event->fast->advent),//5
            array('2011-12-24',  'comment3', $config->event->fast->advent),//6
            array('2011-12-25',  'comment3', $config->event->fast->advent),//7
            array('2011-12-26',  'comment2', $config->event->fast->advent),//1
            array('2011-12-27',  'comment4', $config->event->fast->advent),//2
            array('2011-12-28',  'xerophagy', $config->event->fast->advent),//3
            array('2011-12-29',  'comment4', $config->event->fast->advent),//4
            array('2011-12-30',  'xerophagy', $config->event->fast->advent),//5
            array('2011-12-31',  'comment3', $config->event->fast->advent),//6 
            
        );
    }

    /**
     * @dataProvider providerRemark
     * @param <type> $year
     * @param <type> $except
     */
    public function testRemark($day, $remark, $eventId) {
        $fastTable = new MfCalendarOrthodoxyFast();        
        // проверяем ремарки с ожидаемым      
        $info = $fastTable->getPostInfo($day);
        $this->assertEquals($eventId, $info['event_id']);
        $this->assertEquals($remark, $info['remark']);
    }
}