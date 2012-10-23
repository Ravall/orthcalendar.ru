<?php
    require_once dirname(__FILE__) . '/ObjectSuite.php';
    require_once SANCTA_PATH . '/Article.php';
    require_once SANCTA_PATH . '/Event.php';
    require_once SANCTA_PATH . '/Peer/Article.php';
    require_once SANCTA_PATH . '/Peer/Event.php';

    class ArticleTest extends ObjectSuite {
        protected $model;
        protected $className = 'Sancta_Article';

        /**
         * Тестируем возможности текста
         */
        public function testText() {            
            $model = Sancta_Peer_Article::create();
            $this->_testText($model);
            $model = Sancta_Peer_Article::create(array(            
                'content' => $content = TestTools::getRandomString(),
                'title' => $title =  TestTools::getRandomString(),            
            ));
            $this->_testCreateObjectTextNotNull($model, $content, $title);
            $this->assertEquals(Sancta_Peer_Article::getById($model->getId()), $model);
        }    
        /**
         * Проверяем обновление парамеров
         */
        public function testUpdate() {         
            $model = Sancta_Peer_Article::create();
            $update = array(             
                Sancta_Text::CONTENT => $newContent = TestTools::getRandomString(),
                Sancta_Text::ANNONCE => $newAnnonce = TestTools::getRandomString(),
                Sancta_Text::TITLE => $newTitle = TestTools::getRandomString(),             
            );
            $model->update($update);         
            $this->assertEquals($newContent, $model->getContent());
            $this->assertEquals($newAnnonce, $model->getAnnonce());
            $this->assertEquals($newTitle, $model->getTitle());
            $update = array(                        
                Sancta_Text::TITLE => $newTitle = TestTools::getRandomString(),             
            );
            $model->update($update);         
            $this->assertEquals($newTitle, $model->getTitle(), 'заголовок не обновился');         
        }
        
        /**
         * тестируем изменение статусов у статей.
         */    
        public function testUpdateStatus() {
            $model = Sancta_Peer_Article::create(array(
                'content' => TestTools::getRandomString(),
                'title' => TestTools::getRandomString()
            ));
            $row = $this->db->fetchRow(
                'select * from mf_system_object where id = ' . $model->getId()
            );
            $this->assertEquals(STATUS_ACTIVE, $row['status']);
            $model->setPaused();
            $row = $this->db->fetchRow(
                'select * from mf_system_object where id = ' . $model->getId()
            );
            $this->assertEquals(STATUS_PAUSE, $row['status']);
            $this->assertEquals(STATUS_PAUSE, $model->getStatus());
            $model->setDeleted();
            $row = $this->db->fetchRow(
                'select * from mf_system_object where id = ' . $model->getId()
            );
            $this->assertEquals(STATUS_DELETED, $row['status']);
            $this->assertEquals(STATUS_DELETED, $model->getStatus());
        }
        
        /**
         * тестируем привязку статьи к событию
         */
        public function testRelateToEvent() {            
            $event = Sancta_Peer_Event::create();
            $model = Sancta_Peer_Article::create(array(
                'content' => $content = TestTools::getRandomString(),
                'title' => $title =  TestTools::getRandomString()
            ));
            $model->relateToEvent($event->getId());
            $result = $this->db->fetchRow(
                'select count(*) cnt from mf_system_relation where id = ' . $model->getId()
              . ' and parent_id = ' . $event->getId()
            );
            $this->assertEquals(1, $result['cnt']);
        }

      


        public function testGetRelatedEvents() {
            $model = Sancta_Peer_Article::create(array(
                'content' => TestTools::getRandomString(),
                'title' => TestTools::getRandomString()
            ));
            $arrayOfEvent = array(11,12,13);
            foreach ($arrayOfEvent as $id) {
                $model->relateToEvent($id);
            }
            $relatedEventsIds = $model->getRelatedEvents();
            $this->assertEquals($arrayOfEvent, $relatedEventsIds);
        }

        /**
         * Тестируем установку массива связей
         * 
         * @covers  Sancta_Article::setRelates
         */
        public function testSetRelates() {
            $model = Sancta_Peer_Article::create(array(
                'content' => TestTools::getRandomString(),
                'title' => TestTools::getRandomString()
            ));
            $arrayOfEvent = array(11, 12, 13);
            foreach ($arrayOfEvent as $id) {
                $model->relateToEvent($id);
            }
            $newArray = array(22, 23, 11);
            $this->assertTrue($model->setRelates($newArray));
            $actualRelates = $model->getRelatedEvents();
            sort($actualRelates);
            sort($newArray);
            $this->assertEquals($newArray, $model->getRelatedEvents());
            $model->setRelates(array('22 ',' 23',' 11'));
            $this->assertEquals($newArray, $model->getRelatedEvents());
        }

        
    }