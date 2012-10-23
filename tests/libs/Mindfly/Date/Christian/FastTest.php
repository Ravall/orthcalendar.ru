<?php
require_once realpath(dirname(__FILE__).'/../../../../../') . '/config/init.php'; 
require_once PATH_TESTS . '/init.php';
require_once PATH_LIBS . '/Mindfly/Date/Christian/Fast.php';

/**
 * Тестирование модели православные посты
 *
 * @author ravall
 */
class Mindfly_Date_Christian_FastTest extends PHPUnit_Framework_TestCase {
    private $tableFast;

    /**
     * Провайдер для проверки периода поста
     */
    public function providerFastPerod() {
        return array(
            array('advent', '2010', '2010-11-28', '2011-01-06', 'adventFastBegin', 'adventFastEnd'),
            array('advent', '2013', '2013-11-28', '2014-01-06', 'adventFastBegin', 'adventFastEnd'),
            array('assumption', '2010', '2010-08-14', '2010-08-27', 'assumptionFastBegin', 'assumptionFastEnd'),
            array('assumption', '2011', '2011-08-14', '2011-08-27', 'assumptionFastBegin', 'assumptionFastEnd'),
            array('apostels', '2010', '2010-05-31', '2010-07-11', 'apostlesFastBegin', 'apostlesFastEnd'),
            array('apostels', '2011', '2011-06-20', '2011-07-11', 'apostlesFastBegin', 'apostlesFastEnd'),
            array('lent', '2011', '2011-03-07', '2011-04-23','lentFastBegin', 'lentFastEnd'),
            array('lent', '2012', '2012-02-27', '2012-04-14','lentFastBegin', 'lentFastEnd')
        );
    }

    /**
     *  тестируем период поста
     *
     * @dataProvider providerFastPerod
     * @param <type> $year
     * @param <type> $result
     */
    public function testFastPerod($fast, $year, $from, $to, $functionBegin, $functionEnd) {
        $this->tableFast = new Mindfly_Date_Christian_Fast();
        $resultActual = $this->tableFast->$fast($year);
        $date = new Mindfly_Date($from);
        do {
            $excepted[] = $date->getDay();
            $date->nextDay();
        } while ($date->getDay() <= $to);        
        $this->assertEquals($excepted, $resultActual);
        $this->assertEquals($from, Mindfly_Date_Christian_Fast::$functionBegin($year));
        $this->assertEquals($to, Mindfly_Date_Christian_Fast::$functionEnd($year));
    }



    
    
}