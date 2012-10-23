<?php
require_once realpath(dirname(__FILE__).'/../../../') . '/config/init.php';
require_once PATH_TESTS . '/init.php';
require_once PATH_LIBS_ZEND. '/Zend/Db/Table/Abstract.php';
require_once PATH_LIBS_ZEND . '/Zend/View/Helper/Abstract.php';
require_once PATH_LIBS . '/Mindfly/Outputs/TreeMenuOutput.php';
require_once PATH_LIBS . '/Mindfly/Date.php';




/**
 * Тестируем методы модели "сетка событий"
 *
 * @author user
 */
class NetTableTest extends PHPUnit_Framework_TestCase {
    private $db;

    public function  setUp() {
        parent::setUp();
        $this->db = Zend_Registry::get('db');
    }

    public function testGetNet() {
        $categoryTable = new MfCalendarCategoryTable();
        $category1 = $categoryTable->create(TestTools::getRandomString(time()));
        $category11 = $categoryTable->create(TestTools::getRandomString(time()));
        $category1->getObject()->addSubObject($category11->getObject());
        $category2 = $categoryTable->create(TestTools::getRandomString(time()));
        $eventCategory1_1 = $category1->addEvent();
        $eventCategory1_1->addDate('2010-01-01');
        $eventCategory1_1->addDate('2010-01-02');
        $eventCategory1_2 = $category1->addEvent();
        $eventCategory1_2->addDate('2010-01-03');
        $eventCategory1_2->addDate('2010-02-02');

        $eventCategory11_1 = $category11->addEvent();
        $eventCategory11_1->addDate('2010-01-01');
        $eventCategory11_1->addDate('2010-01-04');
        $eventCategory11_1->addDate('2010-01-05');
        $eventCategory11_2 = $category11->addEvent();
        $eventCategory11_2->addDate('2010-01-06');
        $eventCategory11_2->addDate('2010-02-03');

        $eventCategory2 = $category2->addEvent();
        $eventCategory2->addDate('2010-01-15');
        $eventCategory2->addDate('2011-01-15');



        $netClass = new MfCalendarNetTable();
        $nets = $netClass->getNet('2010', null,null,array(
            $eventCategory1_1->id, $eventCategory1_2->id, $eventCategory11_1->id,
            $eventCategory11_2->id, $eventCategory2->id
        ));
        $fullDates = array();
        foreach ($nets as $net) {
            $fullDates[] =  $net->full_date;
        }
        $this->assertEquals(array(
            '2010-01-01', '2010-01-01', '2010-01-02', '2010-01-03',
            '2010-01-04', '2010-01-05', '2010-01-06', '2010-01-15',
            '2010-02-02','2010-02-03'

        ), $fullDates);

        $nets = $netClass->getNet('2010', '01', null, array(
            $eventCategory1_1->id, $eventCategory1_2->id, $eventCategory11_1->id,
            $eventCategory11_2->id
        ));
        $fullDates = array();
        foreach ($nets as $net) {
            $fullDates[] =  $net->full_date;
        }
        $this->assertEquals(array(
            '2010-01-01', '2010-01-01', '2010-01-02', '2010-01-03',
            '2010-01-04', '2010-01-05', '2010-01-06'
        ), $fullDates);

        $nets = $netClass->getNet('2010', '01', '01', array(
            $eventCategory1_1->id, $eventCategory11_1->id
        ));
        $fullDates = array();
        foreach ($nets as $net) {
            $fullDates[] =  $net->full_date;
        }
        $this->assertEquals(array(
            '2010-01-01', '2010-01-01'
        ), $fullDates);

    }



    //put your code here
}
?>
