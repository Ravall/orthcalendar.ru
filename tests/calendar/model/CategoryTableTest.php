<?php
require_once realpath(dirname(__FILE__).'/../../../') . '/config/init.php';
require_once PATH_TESTS . '/init.php';
require_once PATH_LIBS_ZEND . '/Zend/View/Helper/Abstract.php';
require_once PATH_LIBS . '/Mindfly/Outputs/TreeMenuOutput.php';

require_once PATH_LIBS_ZEND. '/Zend/Db/Table/Abstract.php';
require_once SYSTEM_PATH.'/model/MfSystemObjectTable.php';
require_once SYSTEM_PATH.'/model/MfSystemTextTable.php';
require_once SYSTEM_PATH.'/model/MfSystemTextRow.php';
require_once SYSTEM_PATH.'/model/MfSystemObjectTextTable.php';

require_once CALENDAR_PATH . '/model/MfCalendarCategoryTable.php';
require_once CALENDAR_PATH . '/model/MfCalendarCategoryRow.php';

/**
 * Description of objectTableTest
 *
 * @author user
 */
class CategoryTableTest extends PHPUnit_Framework_TestCase {
    private $db;

    public function  setUp() {
        parent::setUp();
        $this->db = Zend_Registry::get('db');
    }

    /**
     * проверяем правильность создания категории
     */
    public function testCreatCategory() {

        $categoryClass = new MfCalendarCategoryTable();
        $title = TestTools::getRandomString(time());
        $category = $categoryClass->create($title);

        $result = $this->db->fetchRow(
                'SELECT o.*, t.* FROM mf_calendar_category c '
              . ' LEFT JOIN mf_system_object o ON c.id = o.id '
              . ' LEFT JOIN mf_system_object_text ot ON ot.lang = "' . LANG_RU . '"'
              . '  AND system_object_id = o.id'
              . ' LEFT JOIN mf_system_text t ON t.id = ot.system_text_id'
              . ' WHERE c.id = ' . $category->id);

        
        $this->assertEquals('mf_calendar_category', $result['created_class']);
        $this->assertEquals($title, $result['title']);

        $title_en = TestTools::getRandomString('en_' . time());
        $categoryClass = new MfCalendarCategoryTable();
        $title = TestTools::getRandomString(time());
        $category = $categoryClass->create($title_en, LANG_EN);

        $result = $this->db->fetchRow(
                'SELECT o.*, t.* FROM mf_calendar_category c '
              . ' LEFT JOIN mf_system_object o ON c.id = o.id '
              . ' LEFT JOIN mf_system_object_text ot ON ot.lang = "' . LANG_EN . '"'
              . '  AND system_object_id = o.id'
              . ' LEFT JOIN mf_system_text t on t.id = ot.system_text_id'
              . ' WHERE c.id = ' . $category->id);

        $this->assertEquals('mf_calendar_category', $result['created_class']);
        $this->assertEquals($title_en, $result['title']);
    }

    public function testIsExist() {
        $categoryClass = new MfCalendarCategoryTable();        
        $title = TestTools::getRandomString(time());        
        $categoryClass->create($title);        
        $categoryClass->create(TestTools::getRandomString(time()));        
        $categoryClass->create(TestTools::getRandomString(time()));
        $this->assertTrue($categoryClass->isExist($title));
        $title = TestTools::getRandomString('new');
        $this->assertFalse($categoryClass->isExist($title));
        $categoryClass->create($title);
        $this->assertTrue($categoryClass->isExist($title));
    }

    /**
     * проверяем выбрасывания исключения при "плохой" категории
     * @expectedException Exception
     * @dataProvider providerInvalidTitle
     */
    public function testCreatExceptionCategory($title,$errorMess) {
        $categoryClass = new MfCalendarCategoryTable();
        $categoryClass->create($title);
    }

    public function providerInvalidTitle() {
        return array(
            array('sdfffffffffffffffffffffffffffffffffffffffffffffffffffffffffff',
                   array('Название категории слишком длинное')
                 ),
            array('s',
                   array('Название категории слишком короткое')
                 ),
            array('<tag>ssd</tag>',
                   array('Название категории невалидно')
                 ),
        );
    }

    /**
     * Тестируем получение категории
     */
    public function testGet() {
        $categoryClass = new MfCalendarCategoryTable();
        $categoryClass->create(TestTools::getRandomString(time()));
        $category = $categoryClass->create(TestTools::getRandomString(time()));
        $categoryClass->create(TestTools::getRandomString(time()));
        $categoryGet = $categoryClass->get($category->id);
        $this->assertEquals($category, $categoryGet);
    }


}
?>
