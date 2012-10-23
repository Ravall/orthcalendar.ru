<?php
require_once realpath(dirname(__FILE__).'/../../../') . '/config/init.php';
require_once PATH_TESTS . '/init.php';
require_once PATH_LIBS_ZEND. '/Zend/Db/Table/Abstract.php';

require_once SYSTEM_PATH.'/model/MfSystemObjectTable.php';
require_once SYSTEM_PATH.'/model/MfSystemTextTable.php';
require_once SYSTEM_PATH.'/model/MfSystemTextRow.php';
require_once SYSTEM_PATH.'/model/MfSystemObjectTextTable.php';

/* 
 * Тест системного текста
 */
class TextRowTest extends PHPUnit_Framework_TestCase {

    private $db;

    public function  setUp() {
        parent::setUp();
        $this->db = Zend_Registry::get('db');
    }
    
    public function testIsEmpty() {
        $object = MfSystemObjectTable::register(array('created_class' => __CLASS__));
        $this->assertTrue($object->getText()->isEmpty());
        $object->setText($text = TestTools::getRandomSimpleText());
        $this->assertFalse($object->getText()->isEmpty());
        $this->assertTrue($object->getText(LANG_EN)->isEmpty());
    }

    public function testUpdateText() {
         $object = MfSystemObjectTable::register(array('created_class' => __CLASS__));
         $title_ru = TestTools::getRandomString();
         $annonce_ru = TestTools::getRandomString();
         $content_ru = TestTools::getRandomString();
         $text_ru = array(
             'title' => $title_ru,
             'annonce' => $annonce_ru,
             'content' => $content_ru
         );
         $object->setText($text_ru);
         $content_new = TestTools::getRandomString();
         $object->getText()->updateText(array('content' => $content_new));

         $result = $this->db->fetchRow(
             'select t.* from mf_system_text t '
           . ' left join mf_system_object_text st '
           . ' ON t.id = st.system_text_id '
           . ' WhERE st.lang = ? and st.system_object_id = ?',
           array(LANG_RU, $object->id)
         );

         $this->assertEquals($result['title'], $title_ru);
         $this->assertEquals($result['annonce'], $annonce_ru);
         $this->assertEquals($result['content'], $content_new);
    }
}
?>
