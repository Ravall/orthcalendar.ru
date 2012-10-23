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
class NetRowTest extends PHPUnit_Framework_TestCase {
    private $db;

    public function  setUp() {
        parent::setUp();
        $this->db = Zend_Registry::get('db');
    }

    /**
     * тест на правильное выполнение функции save
     */
    public function testSaveNet() {
        $categoryClass = new MfCalendarCategoryTable();
        $category = $categoryClass->create(TestTools::getRandomString(time()));
        $event = $category->addEvent();

        $netClass = new MfCalendarNetTable();
        $net = $netClass->createRow();
        $net->event_id = $event->id;
        $net->full_date = '1983-11-03';
        $net->save();
        // проверяем правльно ли распарсилась дата
        $this->assertEquals('1983', $net->year);
        $this->assertEquals('11', $net->month);
        $this->assertEquals('03', $net->day);
    }

    
    //put your code here
}
?>
