<?php
require_once realpath(dirname(__FILE__).'/../../../') . '/config/init.php';
require_once PATH_TESTS . '/init.php';

require_once PATH_LIBS_ZEND. '/Zend/Db/Table/Abstract.php';
require_once SYSTEM_PATH.'/model/MfSystemObjectTable.php';
require_once SYSTEM_PATH.'/model/MfSystemTextTable.php';
require_once SYSTEM_PATH.'/model/MfSystemTextRow.php';
require_once SYSTEM_PATH.'/model/MfSystemObjectTextTable.php';

require_once CALENDAR_PATH . '/model/MfCalendarOrthodoxyFastTable.php';
require_once CALENDAR_PATH . '/model/MfCalendarOrthodoxyFastRow.php';

/**
 * Description of objectTableTest
 *
 * @author user
 */
class CalendarOrthodoxyFastTableTest extends PHPUnit_Framework_TestCase {
    private $db;

    public function  setUp() {
        parent::setUp();
        $this->db = Zend_Registry::get('db');
    }

    /**
     * @cover (private) addDate
     * @cover addNoPost
     * @cover addPost
     */
    public function testAddPost() {
        $postTable = new MfCalendarOrthodoxyFastTable();
        $categoryClass = new MfCalendarCategoryTable();
        $category = $categoryClass->create(TestTools::getRandomString(time()));
        $category->addEvent();
        $event = $category->addEvent();
        $post = $postTable->addPost($date = '2010-11-01', $event->id, $remark1 = TestTools::getRandomString());
        /**
         * Полсе этого в базе должна быть информация о посте
         */
        $result = $this->db->fetchAll($sql =
                'SELECT o.*, t.*,p.* FROM mf_calendar_orthodoxy_fast p '
              . ' LEFT JOIN mf_system_object o ON p.object_id = o.id '
              . ' LEFT JOIN mf_system_object_text ot ON ot.lang = "' . LANG_DEFAULT . '"'
              . '  AND system_object_id = o.id'
              . ' LEFT JOIN mf_system_text t on t.id = ot.system_text_id'
              . ' WHERE p.event_id = ' . $event->id . ' order by z_index');
        
        $this->assertEquals($date, $result[0]['full_date']);
        $this->assertEquals($remark1, $result[0]['content']);
        $this->assertEquals(0, $result[0]['z_index']);
        $this->assertEquals(1, $result[0]['post']);
        $this->assertEquals(1, count($result));

        /**
         * Проверяем несколько записей на одно событие
         */
        $post = $postTable->addPost($date, $event->id, $remark2 = TestTools::getRandomString(), 1);
        $result = $this->db->fetchAll($sql);
        
        $this->assertEquals(2, count($result));
        $this->assertEquals($date, $result[0]['full_date']);
        $this->assertEquals($remark1, $result[0]['content']);
        $this->assertEquals(0, $result[0]['z_index']);
        
        $this->assertEquals($date, $result[1]['full_date']);
        $this->assertEquals($remark2, $result[1]['content']);
        $this->assertEquals(1, $result[1]['z_index']);

        /**
         * Проверяем не пост
         */
        $post = $postTable->addNoPost($date, $event->id, 2);
        $result = $this->db->fetchAll($sql);
        $this->assertEquals($date, $result[2]['full_date']);        
        $this->assertEquals(2, $result[2]['z_index']);
        $this->assertEquals(0, $result[2]['post']);
        $count = count($result);

        /**
         * проверяем работу индекса
         * количество не должно изменится
         * и значения должны обновиться
         */
        $post = $postTable->addPost($date, $event->id, $remark3 = TestTools::getRandomString(), 2);
        $result = $this->db->fetchAll($sql);
        $this->assertTrue($count > 1);
        $this->assertEquals($count, count($result));
        $info = $postTable->getPostInfo($date);
        $this->assertEquals(true, $info->isPost());
        $this->assertEquals($remark3, $info->getRemark());

    }

    /**
     * @cover MfCalendarOrthodoxyFastRow::isPost
     * @cover MfCalendarOrthodoxyFastRow::getRemark
     * @cover getPostInfo
     */
    public function testGetPostInfo() {
        $date = '2009-11-02';
        // подчистим перед экспериментом
        $this->db->query('delete from mf_calendar_orthodoxy_fast where full_date = "' . $date . '"');
        $postTable = new MfCalendarOrthodoxyFastTable();
        $categoryClass = new MfCalendarCategoryTable();
        $category = $categoryClass->create(TestTools::getRandomString(time()));
        $category->addEvent();
        $event = $category->addEvent();
        $postTable->addPost($date, $event->id, $remark1 = TestTools::getRandomString());
        $info = $postTable->getPostInfo($date);        
        $this->assertTrue($info->isPost());
        $this->assertEquals($event->id, $info->event_id);
        $this->assertEquals($remark1, $info->getRemark());
        $postTable->addPost($date, $event->id, $remark2 = TestTools::getRandomString(), 1);
        $info = $postTable->getPostInfo($date);
        $this->assertEquals($remark2, $info->getRemark());
        $postTable->addNoPost($date, $event->id, 2);
        $info = $postTable->getPostInfo($date);        
        $this->assertFalse($info->isPost());
    }

}