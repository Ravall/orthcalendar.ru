<?php
/* 
 * Тест мясоедов
 */
require_once realpath(dirname(__FILE__).'/../../../../../') . '/config/init.php';
require_once dirname(__FILE__) . '/Fake/FakeConfigRemark.php';
require_once PATH_TESTS . '/init.php';
require_once PATH_LIBS . '/Mindfly/Date/Christian/Meatfare.php';
require_once PATH_LIBS . '/Mindfly/Date.php';

class Mindfly_Date_Christian_MeatfareTest extends PHPUnit_Framework_TestCase {

    private $tableMeatfare;

    public function  setUp() {
        parent::setUp();
        $this->tableMeatfare = new Mindfly_Date_Christian_Meatfare(new FakeConfigRemark());
    }

    
    public function providerMeatfare() {
        return array(
            array('2010',
                  '2010-08-28', '2010-11-27',
                  array(
                      '2010-08-28' => 'nofast',
                      '2010-09-27' => 'comment5',
                      '2010-09-11' => 'comment5'
                  ),
                  'xerophagy',
                'autumn'
            ),
            array('2011',
                  '2011-08-28', '2011-11-27',
                  array(
                      '2011-08-28' => 'nofast',
                      '2011-09-27' => 'comment5',
                      '2011-09-11' => 'comment5'
                  ),
                  'xerophagy',
                'autumn'
            ),
            array('2013',
                  '2013-08-28', '2013-11-27',
                  array(
                      '2013-08-28' => 'comment1',
                      '2013-09-27' => 'comment5',
                      '2013-09-11' => 'comment5'
                  ),
                  'xerophagy',
                'autumn'
            ),
            array('2010',
                  '2010-01-07', '2010-02-14',
                  array(
                      '2010-01-18' => 'comment5', //сочельник
                      '2010-01-07' => 'nofast',   //святки
                      '2010-01-08' => 'nofast',   //святки
                      '2010-01-09' => 'nofast',   //святки
                      '2010-01-10' => 'nofast',   //святки
                      '2010-01-11' => 'nofast',   //святки
                      '2010-01-12' => 'nofast',   //святки
                      '2010-01-13' => 'nofast',   //святки
                      '2010-01-14' => 'nofast',   //святки
                      '2010-01-15' => 'nofast',   //святки
                      '2010-01-16' => 'nofast',   //святки
                      '2010-01-17' => 'nofast',   //святки
                      '2010-01-24' => 'nofast',   // мытаря и фарисея
                      '2010-01-25' => 'nofast',   // мытаря и фарисея
                      '2010-01-26' => 'nofast',   // мытаря и фарисея
                      '2010-01-27' => 'nofast',   // мытаря и фарисея
                      '2010-01-28' => 'nofast',   // мытаря и фарисея
                      '2010-01-29' => 'nofast',   // мытаря и фарисея
                      '2010-01-30' => 'nofast',   // мытаря и фарисея
                      '2010-02-07' => 'comment3', // масленица
                      '2010-02-08' => 'comment3', // масленица
                      '2010-02-09' => 'comment3', // масленица
                      '2010-02-10' => 'comment3', // масленица
                      '2010-02-11' => 'comment3', // масленица
                      '2010-02-12' => 'comment3', // масленица
                      '2010-02-13' => 'comment3', // масленица                                            
                  ),
                  'comment1',
                'winter'
            ),
            array('2011',
                  '2011-01-07', '2011-03-06',
                  array(
                      '2011-01-18' => 'comment5', //сочельник
                      '2011-01-07' => 'nofast',   //святки
                      '2011-01-08' => 'nofast',   //святки
                      '2011-01-09' => 'nofast',   //святки
                      '2011-01-10' => 'nofast',   //святки
                      '2011-01-11' => 'nofast',   //святки
                      '2011-01-12' => 'nofast',   //святки
                      '2011-01-13' => 'nofast',   //святки
                      '2011-01-14' => 'nofast',   //святки
                      '2011-01-15' => 'nofast',   //святки
                      '2011-01-16' => 'nofast',   //святки
                      '2011-01-17' => 'nofast',   //святки
                      '2011-02-13' => 'nofast',   // мытаря и фарисея
                      '2011-02-14' => 'nofast',   // мытаря и фарисея
                      '2011-02-15' => 'nofast',   // мытаря и фарисея
                      '2011-02-16' => 'nofast',   // мытаря и фарисея
                      '2011-02-17' => 'nofast',   // мытаря и фарисея
                      '2011-02-18' => 'nofast',   // мытаря и фарисея
                      '2011-02-19' => 'nofast',   // мытаря и фарисея
                      '2011-02-27' => 'comment3', // масленица
                      '2011-02-28' => 'comment3', // масленица
                      '2011-03-01' => 'comment3', // масленица
                      '2011-03-02' => 'comment3', // масленица
                      '2011-03-03' => 'comment3', // масленица
                      '2011-03-04' => 'comment3', // масленица
                      '2011-03-05' => 'comment3', // масленица
                  ),
                  'comment1',
                'winter'
            ),
            array('2012',
                  '2012-01-07', '2012-02-26',
                  array(
                      '2012-01-18' => 'comment5', //сочельник
                      '2012-01-07' => 'nofast',   //святки
                      '2012-01-08' => 'nofast',   //святки
                      '2012-01-09' => 'nofast',   //святки
                      '2012-01-10' => 'nofast',   //святки
                      '2012-01-11' => 'nofast',   //святки
                      '2012-01-12' => 'nofast',   //святки
                      '2012-01-13' => 'nofast',   //святки
                      '2012-01-14' => 'nofast',   //святки
                      '2012-01-15' => 'nofast',   //святки
                      '2012-01-16' => 'nofast',   //святки
                      '2012-01-17' => 'nofast',   //святки
                      '2012-02-05' => 'nofast',   // мытаря и фарисея
                      '2012-02-06' => 'nofast',   // мытаря и фарисея
                      '2012-02-07' => 'nofast',   // мытаря и фарисея
                      '2012-02-08' => 'nofast',   // мытаря и фарисея
                      '2012-02-09' => 'nofast',   // мытаря и фарисея
                      '2012-02-10' => 'nofast',   // мытаря и фарисея
                      '2012-02-11' => 'nofast',   // мытаря и фарисея
                      '2012-02-19' => 'comment3', // масленица
                      '2012-02-20' => 'comment3', // масленица
                      '2012-02-21' => 'comment3', // масленица
                      '2012-02-22' => 'comment3', // масленица
                      '2012-02-23' => 'comment3', // масленица
                      '2012-02-24' => 'comment3', // масленица
                      '2012-02-25' => 'comment3', // масленица
                  ),
                  'comment1',
                'winter'
            ),
            array('2010',
                  '2010-04-04', '2010-05-30',
                  array(                      
                      '2010-04-04' => 'nofast',   // Пасхальной недели
                      '2010-04-05' => 'nofast',   // Пасхальной недели
                      '2010-04-06' => 'nofast',   // Пасхальной недели
                      '2010-04-07' => 'nofast',   // Пасхальной недели
                      '2010-04-08' => 'nofast',   // Пасхальной недели
                      '2010-04-09' => 'nofast',   // Пасхальной недели
                      '2010-04-10' => 'nofast',   // Пасхальной недели
                      '2010-05-23' => 'nofast',   // троицкой недели
                      '2010-05-24' => 'nofast',   // троицкой недели
                      '2010-05-25' => 'nofast',   // троицкой недели
                      '2010-05-26' => 'nofast',   // троицкой недели
                      '2010-05-27' => 'nofast',   // троицкой недели
                      '2010-05-28' => 'nofast',   // троицкой недели
                      '2010-05-29' => 'nofast',   // троицкой недели
                  ),
                  'comment1',
                'spring'
            ),
            array('2011',
                  '2011-04-24', '2011-06-19',
                  array(
                      '2011-04-24' => 'nofast',   // Пасхальной недели
                      '2011-04-25' => 'nofast',   // Пасхальной недели
                      '2011-04-26' => 'nofast',   // Пасхальной недели
                      '2011-04-27' => 'nofast',   // Пасхальной недели
                      '2011-04-28' => 'nofast',   // Пасхальной недели
                      '2011-04-29' => 'nofast',   // Пасхальной недели
                      '2011-04-30' => 'nofast',   // Пасхальной недели
                      '2011-06-12' => 'nofast',   // троицкой недели
                      '2011-06-13' => 'nofast',   // троицкой недели
                      '2011-06-14' => 'nofast',   // троицкой недели
                      '2011-06-15' => 'nofast',   // троицкой недели
                      '2011-06-16' => 'nofast',   // троицкой недели
                      '2011-06-17' => 'nofast',   // троицкой недели
                      '2011-06-18' => 'nofast',   // троицкой недели
                  ),
                  'comment1',
                'spring'
            ),
            array('2012',
                  '2012-04-15', '2012-06-10',
                  array(
                      '2012-04-15' => 'nofast',   // Пасхальной недели
                      '2012-04-16' => 'nofast',   // Пасхальной недели
                      '2012-04-17' => 'nofast',   // Пасхальной недели
                      '2012-04-18' => 'nofast',   // Пасхальной недели
                      '2012-04-19' => 'nofast',   // Пасхальной недели
                      '2012-04-20' => 'nofast',   // Пасхальной недели
                      '2012-04-21' => 'nofast',   // Пасхальной недели
                      '2012-06-03' => 'nofast',   // троицкой недели
                      '2012-06-04' => 'nofast',   // троицкой недели
                      '2012-06-05' => 'nofast',   // троицкой недели
                      '2012-06-06' => 'nofast',   // троицкой недели
                      '2012-06-07' => 'nofast',   // троицкой недели
                      '2012-06-08' => 'nofast',   // троицкой недели
                      '2012-06-09' => 'nofast',   // троицкой недели
                  ),
                  'comment1',
                'spring'
            ),
            array('2010',
                  '2010-07-12', '2010-08-13',
                  array(
                      '2010-07-12' => 'nofast',   // День апостолов
                  ),
                  'xerophagy',
                'summer'
            ),
            array('2011',
                  '2011-07-12', '2011-08-13',
                  array(
                      '2011-07-12' => 'nofast',   // День апостолов
                  ),
                  'xerophagy',
                'summer'
            ),
            array('2012',
                  '2012-07-12', '2012-08-13',
                  array(
                      '2012-07-12' => 'nofast',   // День апостолов
                  ),
                  'xerophagy',
                'summer'
            ),
            array('2013',
                  '2013-07-12', '2013-08-13',
                  array(
                      '2013-07-12' => 'comment1',   // День апостолов
                  ),
                  'xerophagy',
                'summer'
            ),
        );
    }

    /**
     * @dataProvider providerMeatfare
     * @param <type> $year  год для проверки
     * @param <type> $dateBegin дата начала мясоеда
     * @param <type> $dateEnd дата конца мясоеда
     * @param <type> $specialDays особые дни во время мясоеда
     * @param <type> $remarkIn34 заметка в среду и пятницу
     * @param <type> $meatfare тип мясоеда
     */
    public function testMeatfare($year, $dateBegin, $dateEnd, $specialDays, $remarkIn34, $meatfare) {
        $time = new Mindfly_Date($dateBegin);
        $meatfare = $this->tableMeatfare->$meatfare($year);
        $this->assertFalse(empty ($meatfare), 'Не удалось получить мясоеда');
        $countDays = 0;        
        do {
            $countDays++;                        
            $time->nextDay();
        } while ($time->getDay() != date('Y-m-d',  strtotime($dateEnd . ' +1 day')));        
        $this->assertEquals(
                $countDays, count($meatfare),
                'есть лишние дни мясоеда. Получено:' . count($meatfare)
        );
    }

}

?>