<?php
require_once realpath(dirname(__FILE__).'/../../../') . '/config/init.php';
require_once PATH_TESTS . '/init.php';
require_once PATH_LIBS_ZEND . '/Zend/View/Helper/Abstract.php';
require_once PATH_LIBS . '/Mindfly/Outputs/TreeMenuOutput.php';
require_once PATH_LIBS_ZEND. '/Zend/Db/Table/Abstract.php';


/**
 * Description of objectTableTest
 *
 * @author user
 */
class CategoryRowTest extends PHPUnit_Framework_TestCase {
    private $db;

    public function  setUp() {
        parent::setUp();
        $this->db = Zend_Registry::get('db');
    }

    /**
     * Тестируем получение объекта
     */
    public function testGetObject() {
        $categoryClass = new MfCalendarCategoryTable();
        $title = TestTools::getRandomString(time());
        $category = $categoryClass->create($title);
        $objectClass = new MfSystemObjectTable();
        $object = $objectClass->fetchRow('id = ' . $category->id);
        $this->assertEquals($object, $category->getObject());
    }
    
    /**
     * Тестируем получение текста
     */
    public function testGetText() {
        $categoryClass = new MfCalendarCategoryTable();
        $title = TestTools::getRandomString(time());
        $category = $categoryClass->create($title);
        $this->assertEquals($title, $category->getText()->title);
    }


    /**
     * тестируем правильный вызов функции creatEvent
     */
    public function testAddEvent() {
        $categoryClass = new MfCalendarCategoryTable();
        $category = $categoryClass->create(TestTools::getRandomString(time()));
        $event = $category->addEvent();
        $title = TestTools::getRandomString('title');
        $content = TestTools::getRandomString('content');
        $anonce = TestTools::getRandomString('anonce');
        $event->setText(array(
            'title' => $title,
            'content' => $content,
            'annonce' => $anonce
        ));
        $row = $this->db->fetchRow(
                'SELECT ce.*,st.*,so.* FROM mf_calendar_event ce '
              . ' LEFT JOIN mf_system_object so ON so.id = ce.id '
              . ' LEFT JOIN mf_system_object_text sot ON sot.system_object_id = ce.id'
              . ' LEFT JOIN mf_system_text st ON st.id = sot.system_text_id ORDER BY so.id DESC LIMIT 1'
        );
        
        // проверяем правильный тип класса
        $this->assertEquals($row['created_class'],  'mf_calendar_event');        
        // проверяем что родитель правильно установлен
        $this->assertEquals($category->id, $row['parent_id'],'');
        $this->assertEquals($title, $row['title']);
        $this->assertEquals($content, $row['content']);
        $this->assertEquals($anonce, $row['annonce']);
    }


    /**
     * проверяем правильное получение
     * событий категории
     */
    public function testgetEvents() {
        $categoryClass = new MfCalendarCategoryTable();
        $categorySome = $categoryClass->create(TestTools::getRandomString(time()));
        $categorySome->addEvent();
        $categorySome->addEvent();
        $category = $categoryClass->create(TestTools::getRandomString(time()));
        $category->getObject()->addSubObject($categorySome->getObject());
        $events[] = $category->addEvent()->id;
        $events[] = $category->addEvent()->id;

        $exEventsArray = array();       
        foreach ($category->getEvents() as $exEvents) {
            $exEventsArray[] = $exEvents->id;
        }
        sort($events);
        sort($exEventsArray);
        $this->assertEquals($events, $exEventsArray, 'неверное количество');
        $events[] = $category->addEvent()->id;
        sort($events);
        $exEventsArray = array();
        foreach ($category->getEvents() as $exEvents) {
            $exEventsArray[] = $exEvents->id;
        }
        sort($exEventsArray);
        $this->assertEquals($events, $exEventsArray,'неверное количество после добавления');
        $category2 =  $categoryClass->create(TestTools::getRandomString(time()));        
        $this->assertEquals(array(), $category2->getEvents(),'неверное количество у новой категории');
    }

    /**
     * Тестируем выбр разных статусов
     */
    public function testgetEventsStatus() {
        $categoryClass = new MfCalendarCategoryTable();
        $category = $categoryClass->create(TestTools::getRandomString(time()));        

        $event = $category->addEvent();
        $event->getObject()->setDelete();
        $events['deleted'][] = $event->id;

        $event = $category->addEvent();
        $event->getObject()->setDelete();
        $events['deleted'][] = $event->id;

        $event = $category->addEvent();
        $events['active'][] = $event->id;
        
        $event = $category->addEvent();
        $events['active'][] = $event->id;

        $eventsAll = array_merge($events['deleted'], $events['active']);
        sort($eventsAll);
        $exEventsArray = array();
        foreach ($category->getEvents() as $exEvents) {
            $exEventsArray[] = $exEvents->id;
        }
        sort($exEventsArray);        
        $this->assertEquals(
            $eventsAll, $exEventsArray, 'неверное количество status = false'
        );

        $eventsActive = $events['active'];
        sort($eventsActive);
        $exEventsArray = array();
        foreach ($category->getEvents(STATUS_ACTIVE) as $exEvents) {
            $exEventsArray[] = $exEvents->id;
        }
        sort($exEventsArray);
        $this->assertEquals(
            $eventsActive, $exEventsArray,
            'неверное количество status = ' . STATUS_ACTIVE
        );

        $eventsDeleted = $events['deleted'];
        sort($eventsDeleted);
        $exEventsArray = array();
        foreach ($category->getEvents(STATUS_DELETED) as $exEvents) {
            $exEventsArray[] = $exEvents->id;
        }
        sort($exEventsArray);
        $this->assertEquals(
                $eventsDeleted, $exEventsArray,
                'неверное количество status = ' . STATUS_DELETED
        );
    }

    

}