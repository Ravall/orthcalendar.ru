<?php
    require_once dirname(__FILE__) . '/ObjectSuite.php';
    require_once SANCTA_PATH . '/Event.php';
    require_once SANCTA_PATH . '/Peer/Event.php';
    require_once SANCTA_PATH . '/Article.php';
    require_once PATH_BASE . '/models/package/SmartFunction/SmartFunction.php';

    class Sancta_EventTest extends ObjectSuite {
    
        protected $classPeerName = 'Sancta_Peer_Event';
        protected $className = 'Sancta_Event';


        /**
         * тестируем связываение события с категорией
         * 
         * @covers  Sancta_Event::relateToCategory
         */
        public function testRelated() {
            $categoryClass = new MfCalendarCategoryTable();
            $category = $categoryClass->create(TestTools::getRandomString(time()));
            $model = Sancta_Peer_Event::create(array(
                'content' => $content = TestTools::getRandomString(),
                'title' => $title =  TestTools::getRandomString(),
            ));
            $model->relateToCategory($category->id);
            $row = $this->db->fetchRow('select count(*) cnt from mf_system_relation where id = '
                    . $model->getId() . ' and parent_id = '.$category->id);
            $this->assertEquals(1, $row['cnt']);
        }
        
        /**
         * Тестируем возможности текста
         */
        public function testText() {
            $event = Sancta_Peer_Event::create();        
            $this->_testText($event);
            $event = Sancta_Peer_Event::create(array(
                'event_id' => $event->getId(),
                'content' => $content = TestTools::getRandomString(),
                'title' => $title =  TestTools::getRandomString(),
                'smart_function' => 'xxx'
            ));
            $this->_testCreateObjectTextNotNull($event, $content, $title);
            $this->assertEquals(Sancta_Peer_Event::getById($event->getId()), $event);
        }

        
        /**
         * Проверяем обновление парамеров
         */
        public function testUpdate() {            
            $model = Sancta_Peer_Event::create(array(                
                'smart_function' => 'xxx'
            ));
            $update = array(                
                'smart_function' => $newSmartFunction = TestTools::getRandomString(),
                Sancta_Text::CONTENT => $newContent = TestTools::getRandomString(),
                Sancta_Text::ANNONCE => $newAnnonce = TestTools::getRandomString(),
                Sancta_Text::TITLE => $newTitle = TestTools::getRandomString(),             
            );
            $model->update($update);
            $this->assertEquals($newSmartFunction, $model->getSmartFunction(), 'умная функция не обновилась');
            $this->assertEquals($newContent, $model->getContent());
            $this->assertEquals($newAnnonce, $model->getAnnonce());
            $this->assertEquals($newTitle, $model->getTitle());
            $update = array(           
                'smart_function' => $newSmartFunction = TestTools::getRandomString(),           
                Sancta_Text::TITLE => $newTitle = TestTools::getRandomString(),             
            );
            $model->update($update);
            $this->assertEquals($newSmartFunction, $model->getSmartFunction(), 'умная функция не обновилась');
            $this->assertEquals($newTitle, $model->getTitle(), 'заголовок не обновился');         
       }

       /**
        * тестируем создание события, с указанием parent_id
        */
        public function testAddEventWhithParentId() {
            $modelOne = Sancta_Peer_Event::create(array(
                'content' => $content = TestTools::getRandomString(),
                'title' => $title =  TestTools::getRandomString(),
                'parent_id' => $parentId = rand(100,1000)
            ));            
            $row = $this->db->fetchRow('select * from mf_system_object where id = ' . $modelOne->getId());
            $this->assertEquals($parentId, $row['parent_id']);
            $this->assertEquals($parentId, $modelOne->getCategoryId());
        }

       
        /**
         * Тестируем поведение флага "требует перезагрузки", 
         * при обновлении объекта
         */
        public  function testSetReloadedWhenSmartFunctionIsChage() {
            $model = Sancta_Peer_Event::create(array(
                'content' => TestTools::getRandomString(),
                'title' => TestTools::getRandomString(),
                'smart_function' => TestTools::getRandomString()
            ));
            $this->db->query(
                'update mf_calendar_smart_function set reload=0 where id = 
                    (select function_id from mf_calendar_event where id = ' . $model->getId().')'
            );
            $model->update(array('content' => TestTools::getRandomString()));            
            /**
             * если функция не меняется - флаг не меняется тоже
             */
            $row = $this->db->fetchRow(
                'select reload from mf_calendar_smart_function where id = 
                    (select function_id from mf_calendar_event where id = '.$model->getId().')'
            );
            $this->assertEquals(0, $row['reload']);
            $model->update(array(
                'content' => TestTools::getRandomString(),
                'smart_function' => TestTools::getRandomString()
            ));
            /**
             * если функция изменилсь - флаг взводится
             */
            $row = $this->db->fetchRow(
                'select reload from mf_calendar_smart_function where id in 
                    (select function_id from mf_calendar_event where id = '.$model->getId().')'
            );
            $this->assertEquals(1, $row['reload']);
        }
        
        public function testUpdateSmartFunctionWhereNull() {
            $categoryClass = new MfCalendarCategoryTable();
            $category = $categoryClass->create(TestTools::getRandomString(time())); 
            $event = Sancta_Peer_Event::create(array(
                    'content' => $content = TestTools::getRandomString(),
                    'title' => $title =  TestTools::getRandomString(),
                    'smart_function' => '',
                    'parent_id' => $category->id
            ));            
            $this->db->query('update mf_calendar_event set function_id = null where id = '.$event->getId());
            $event = Sancta_Peer_Event::getById($event->getId());
            $event->update(array('content'=>TestTools::getRandomString()));
            $event = $this->db->fetchRow('select function_id from mf_calendar_event where id = '.$event->getId());
            $this->assertTrue($event['function_id']>0, 'новое значение не проставилось');
            
        }
 
                
    }
?>