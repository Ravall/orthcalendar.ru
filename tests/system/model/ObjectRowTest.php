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


/**
 * Description of objectRowTest
 *
 * @author user
 */
class objectRowTest extends Tests_Lib_TestCaseSimple {

    private $db;
    
    public function  setUp() {
        parent::setUp();
        $this->db = Zend_Registry::get('db');
    }

    /**
     * Тестируем обновление времени в поле updated
     *  при изменени объекта
     **/
    public function testTimeUpdate() {
        $object = MfSystemObjectTable::register(array('created_class' => __CLASS__));
        //принудительно меняем на произвольную дату        
        $data = array(
            'updated' => TestTools::getTestData()
        );
        $this->db->update('mf_system_object', $data, 'id = ' . $object->id);
        // обновляем объект
        $object->refresh();
        // проверяем что дата произвольная установилась верно
        $this->assertDataTime($object->updated, date('Y-m-d H:i:s',strtotime(TestTools::getTestData())), 2,  'time incorrect');
        // сохраняем
        $object->save();
        // и проверяем правильно ли обновилась дата
        $this->assertDataTime($object->updated, date('Y-m-d H:i:s',time()), 2, 'time incorrect');
     }

    /*
     * тестируем подставноку статусов
     */
     public function testSetStatus() {
         $object = MfSystemObjectTable::register(array('created_class' => __CLASS__));
         $data = $this->db->fetchRow("SELECT * FROM mf_system_object WHERE id = " . $object->id);
         $this->assertEquals(STATUS_ACTIVE, $data['status']);
         $this->assertEquals(STATUS_ACTIVE, $object->status);

         $object->setPause();
         $data = $this->db->fetchRow("SELECT * FROM mf_system_object WHERE id = " . $object->id);
         $this->assertEquals(STATUS_PAUSE, $data['status']);
         $this->assertEquals(STATUS_PAUSE, $object->status);

         $object->setDelete();
         $data = $this->db->fetchRow("SELECT * FROM mf_system_object WHERE id = " . $object->id);
         $this->assertEquals(STATUS_DELETED, $data['status']);
         $this->assertEquals(STATUS_DELETED, $object->status);

         $object->setActive();
         $data = $this->db->fetchRow("SELECT * FROM mf_system_object WHERE id = " . $object->id);
         $this->assertEquals(STATUS_ACTIVE, $data['status']);
         $this->assertEquals(STATUS_ACTIVE, $object->status);
     }

     /**
      * тестирую функцию addSubObject
      */
     public function testAddSubObject() {
         $object1 = MfSystemObjectTable::register(array('created_class' => __CLASS__));
         $object12 = MfSystemObjectTable::register(array('created_class' => __CLASS__));
         $this->assertEquals(null, $object1->parent_id);
         $this->assertEquals(null, $object12->parent_id);
         $object1->addSubObject($object12);
         $this->assertEquals(null, $object1->parent_id);
         $this->assertEquals($object1->id, $object12->parent_id);
         $parentId = $this->db->fetchOne('SELECT parent_id FROM mf_system_object WHERE id = ' . $object12->id);         
         $this->assertEquals($parentId, $object1->id);
     }

     /**
      * Тестирую фукнцию refrechParentIds, которая
      * вызывается при сохранении объекта
      */
     public function testRefreshParentIds() {
         $object1 = MfSystemObjectTable::register(array('created_class' => __CLASS__));
         $this->assertEquals(null, $object1->parent_ids);
         $object12 = MfSystemObjectTable::register(array('created_class' => __CLASS__));
         $object1->addSubObject($object12);
         $this->assertEquals($object1->id, $object12->parent_ids);
         $object2 = MfSystemObjectTable::register(array('created_class' => __CLASS__));
         $object2->addSubObject($object12);
         $this->assertEquals($object2->id, $object12->parent_ids);
         $object123 = MfSystemObjectTable::register(array('created_class' => __CLASS__));
         $object12->addSubObject($object123);
         $this->assertEquals($object12->id . ',' . $object2->id, $object123->parent_ids);
     }

     public function providerText() {
         return array(
             array(TestTools::getRandomString(), TestTools::getRandomString(), TestTools::getRandomString(),null),
             array(TestTools::getRandomString(), TestTools::getRandomString(), TestTools::getRandomString(), LANG_RU),
             array(TestTools::getRandomString(), TestTools::getRandomString(), TestTools::getRandomString(), LANG_EN),
             array(TestTools::getRandomString(), null, null, null),
             array(null, null, null, null),
         );
     }

     /**
      *  тестируем функцию добавления текста
      * 
      *  @dataProvider providerText
      **/
     public function testAddText($title, $annonce, $content, $lang) {
         $object = MfSystemObjectTable::register(array('created_class' => __CLASS__));
         $object->setText(array(
             'title' => $title,
             'annonce' => $annonce,
             'content' => $content
             ) ,$lang ? $lang : LANG_DEFAULT
          );


          $data = $this->db->fetchAll(
              'SELECT t.*, ot.system_object_id object_id FROM mf_system_object_text ot'
            . ' LEFT JOIN mf_system_text t ON t.id = ot.system_text_id'
            . ' WHERE lang = "' . ($lang ? $lang : LANG_DEFAULT) . '"'
            . ' AND system_object_id = ' . $object->id
          );
          $this->assertEquals(1, count($data));
          $text = current($data);


          $this->assertEquals($text['title'], $title);
          $this->assertEquals($text['annonce'], $annonce);
          $this->assertEquals($text['content'], $content);
          $this->assertEquals($text['object_id'], $object->id);
        }


     /**
      * Тест фукнций MfSystemObjectRow::getText
      *              MfSystemTextRow::isEmpty
      **/
     public function testGetText() {
          $object = MfSystemObjectTable::register(array('created_class' => __CLASS__));
          // добавляем текст
          $object->setText($text = TestTools::getRandomSimpleText());
          $text = $object->getText();
          $this->assertEquals($text['title'], $text->title);
          $this->assertEquals($text['annonce'], $text->annonce);
          $this->assertEquals($text['content'], $text->content);

          // проверяем текст другого языка
          $this->assertTrue($object->getText(LANG_EN)->isEmpty());
          
         
          $object->setText($text = TestTools::getRandomSimpleText(), LANG_EN);
         
          $result = $this->db->fetchRow($sql =
                 'SELECT st.* FROM mf_system_object_text sot'
               . ' LEFT JOIN mf_system_text st ON st.id = sot.system_text_id'
               . ' WHERE sot.system_object_id = ' . $object->id . ' AND sot.lang = "' . LANG_EN . '"'
          );          
          $this->assertEquals($text['title'], $result['title']);
          $this->assertEquals($text['annonce'], $result['annonce']);
          $this->assertEquals($text['content'], $result['content']);
      }

     /**
      * проверяем что одному объекту можно привязать два текста
      **/
     public function testI18nText() {
         $object = MfSystemObjectTable::register(array('created_class' => __CLASS__));
         $title_ru = TestTools::getRandomString();
         $annonce_ru = TestTools::getRandomString();
         $content_ru = TestTools::getRandomString();
         $text_ru = array(
             'title' => $title_ru,
             'annonce' => $annonce_ru,
             'content' => $content_ru
         );
         $title_en = TestTools::getRandomString();
         $annonce_en = TestTools::getRandomString();
         $content_en = TestTools::getRandomString();
         $text_en = array(
             'title' => $title_en,
             'annonce' => $annonce_en,
             'content' => $content_en);
         $object->setText($text_ru);
         $object->setText($text_en, LANG_EN);
         $result = $this->db->fetchRow(
             'select t.* from mf_system_text t '
           . ' left join mf_system_object_text st '
           . ' ON t.id = st.system_text_id '  
           . ' WhERE st.lang = ? and st.system_object_id = ?',
           array(LANG_RU, $object->id)
         );


         $this->assertEquals($result['title'], $title_ru);
         $this->assertEquals($result['annonce'], $annonce_ru);
         $this->assertEquals($result['content'], $content_ru);
              
              
         $result = $this->db->fetchRow(
             'select t.* from mf_system_text t '
           . ' left join mf_system_object_text st '
           . ' ON t.id = st.system_text_id '
           . ' WhERE st.lang = ? and st.system_object_id = ?',
           array(LANG_EN, $object->id)
         );

         $this->assertEquals($result['title'], $title_en);
         $this->assertEquals($result['annonce'], $annonce_en);
         $this->assertEquals($result['content'], $content_en);
     }


     /**
      * тестируем функцию получение дочерних объектов
      */
     public function testGetChildrens() {
         $object1 = MfSystemObjectTable::register(array('created_class' => __CLASS__));
         $object2 = MfSystemObjectTable::register(array('created_class' => __CLASS__));
         $object3 = MfSystemObjectTable::register(array('created_class' => __CLASS__));
         $object4 = MfSystemObjectTable::register(array('created_class' => __CLASS__));
         $object5 = MfSystemObjectTable::register(array('created_class' => __CLASS__));
         $object6 = MfSystemObjectTable::register(array('created_class' => __CLASS__));
         $object1->addSubObject($object2);
         $object1->addSubObject($object3);
         $object3->addSubObject($object4);
         $object3->addSubObject($object5);
         $object5->addSubObject($object6);

         $childrenObject = $object1->getChildrens();
         
         $childrenObjectArray = array();
         foreach ($childrenObject as $child) {
             $childrenObjectArray[] = $child->id;
         }         
         $childArray = array();
         $childArray[] = $object2->id;
         $childArray[] = $object3->id;
         $childArray[] = $object4->id;
         $childArray[] = $object5->id;
         $childArray[] = $object6->id;
         $this->assertEquals($childArray, $childrenObjectArray, 'неверные дочерние елементы для объекта 1');

         $childrenObject = $object3->getChildrens();
         $childrenObjectArray = array();
         foreach ($childrenObject as $child) {
             $childrenObjectArray[] = $child->id;
         }
         $childArray = array();
         $childArray[] = $object4->id;
         $childArray[] = $object5->id;
         $childArray[] = $object6->id;
         $this->assertEquals($childArray, $childrenObjectArray, 'неверные дочерние елементы для объекта 3');

         $childrenObject = $object5->getChildrens();
         $childrenObjectArray = array();
         foreach ($childrenObject as $child) {
             $childrenObjectArray[] = $child->id;
         }
         $childArray = array();
         $childArray[] = $object6->id;
         $this->assertEquals($childArray, $childrenObjectArray, 'неверные дочерние елементы для объекта 5');

         $this->assertEquals(array(),  $object2->getChildrens(), 'неверные дочерние елементы для объекта 2');
         $this->assertEquals(array(),  $object4->getChildrens(), 'неверные дочерние елементы для объекта 4');
         $this->assertEquals(array(),  $object6->getChildrens(), 'неверные дочерние елементы для объекта 6');
     }




}
?>
