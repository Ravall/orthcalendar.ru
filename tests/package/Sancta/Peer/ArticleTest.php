<?php
    require_once dirname(__FILE__) . '/../SanctaTestCase.php';            
    require_once SANCTA_PATH . '/Peer/Article.php';
    require_once SANCTA_PATH . '/Peer/Event.php';
    require_once SANCTA_PATH . '/Article.php';
    require_once SANCTA_PATH . '/Event.php';
    
    class Sancta_Peer_ArticleTest extends SanctaTestCase {
        
         public function testCreate() {
            $model = Sancta_Peer_Article::create();            
            $result = $this->db->fetchRow(
                'select * from mf_system_article where id = ' . $model->getId()
            );
            $this->assertTrue(is_array($result));
        }
        
        public function testGetById() {            
            $model = Sancta_Peer_Article::create(array(
                'content' => $content = TestTools::getRandomString(),
                'title' => $title =  TestTools::getRandomString()
            ));            
            $this->assertEquals($model,Sancta_Peer_Article::getById($model->getId()));            
        }
        
        /**
         * тестируем метод получения списка статей по id события
         */
        public function testGetByEventId() {                        
            $eventOne = Sancta_Peer_Event::create();
            $eventTwo = Sancta_Peer_Event::create();

            $model11 = Sancta_Peer_Article::create(array(
                'content' => TestTools::getRandomString(),
                'title' => TestTools::getRandomString()
            ));
            $model11->relateToEvent($eventOne->getId());
            $model12 = Sancta_Peer_Article::create(array(
                'content' => TestTools::getRandomString(),
                'title' => TestTools::getRandomString()
            ));
            $model12->relateToEvent($eventOne->getId());
            $model21 = Sancta_Peer_Article::create(array(
                'content' => TestTools::getRandomString(),
                'title' => TestTools::getRandomString()
            ));
            $model21->relateToEvent($eventTwo->getId());
            $result = Sancta_Peer_Article::getByEventId($eventOne->getId());                        
            $resultArray = $result->getIterator();
            $this->assertEquals(2, count($resultArray));            
            $this->assertEquals($model11->getId(), $resultArray[0]->getId());
            $this->assertEquals($model12->getId(), $resultArray[1]->getId());
            $this->assertEquals($model11->getAnnonce(), $resultArray[0]->getAnnonce());
            $this->assertEquals($model12->getAnnonce(), $resultArray[1]->getAnnonce());
            $this->assertEquals($model11->getContent(), $resultArray[0]->getContent());
            $this->assertEquals($model12->getContent(), $resultArray[1]->getContent());
            $result = Sancta_Peer_Article::getByEventId($eventTwo->getId());
            $resultArray = $result->getIterator();            
            $this->assertEquals(1, count($resultArray));
            $this->assertEquals($model21->getId(), $resultArray[0]->getId());
            $this->assertEquals($model21->getAnnonce(), $resultArray[0]->getAnnonce());
            $this->assertEquals($model21->getContent(), $resultArray[0]->getContent());
        }
        
        /**
         * проверяем что кеш очищается при добавлении новой статьи
         */
        public function testCache() {            
            $event = Sancta_Peer_Event::create();
            $model1 = Sancta_Peer_Article::create(array(
                'content' => TestTools::getRandomString(),
                'title' => TestTools::getRandomString()
            ));
            $model1->relateToEvent($event->getId());
            $result = Sancta_Peer_Article::getByEventId($event->getId());
            $resultArray = $result->getIterator();
            $this->assertEquals(1, count($resultArray));
            $this->assertEquals($model1->getId(), $resultArray[0]->getId());
            $this->assertEquals($model1->getAnnonce(), $resultArray[0]->getAnnonce());
            $this->assertEquals($model1->getContent(), $resultArray[0]->getContent());
            $result = Sancta_Peer_Article::getByEventId($event->getId());
            $resultArray = $result->getIterator();
            $this->assertEquals(1, count($resultArray));
            $this->assertEquals($model1->getId(), $resultArray[0]->getId());
            $this->assertEquals($model1->getAnnonce(), $resultArray[0]->getAnnonce());
            $this->assertEquals($model1->getContent(), $resultArray[0]->getContent());
        }
        
         /**
         * Проверка очищения кеша при получении списиска привязанных к событию
         * при изменении статьи
         */
        public function testCleanCacheGetByEventId() {            
            $event = Sancta_Peer_Event::create();
            $model = Sancta_Peer_Article::create(array(
                'content' => TestTools::getRandomString(),
                'title' => TestTools::getRandomString()
            ));
            $model->relateToEvent($event->getId());
            $result = Sancta_Peer_Article::getByEventId($event->getId());
            $resultArray = $result->getIterator();
            $this->assertEquals($model->getId(), $resultArray[0]->getId());
            $this->assertEquals($model->getAnnonce(), $resultArray[0]->getAnnonce());
            $this->assertEquals($model->getContent(), $resultArray[0]->getContent());
            $model->setContent(TestTools::getRandomString());            
            $result = Sancta_Peer_Article::getByEventId($event->getId());
             $resultArray = $result->getIterator();
            $this->assertEquals($model->getContent(), $resultArray[0]->getContent());
        }
         
        
       /**
         * Проверка очищения кеша при получении списиска привязанных к событию
         * при изменении статуса
         */
        public function testCleanCacheGetByEventIdWhenObjectDeleted() {            
            $event = Sancta_Peer_Event::create();
            $model1 = Sancta_Peer_Article::create(array(
                'content' => TestTools::getRandomString(),
                'title' => TestTools::getRandomString()
            ));
            $model1->relateToEvent($event->getId());
            $model2 = Sancta_Peer_Article::create(array(
                'content' => TestTools::getRandomString(),
                'title' => TestTools::getRandomString()
            ));
            $model2->relateToEvent($event->getId());
            $result = Sancta_Peer_Article::getByEventId($event->getId());
            $result = $result->statusFilter();
            $resultArray = $result->getIterator();
            $this->assertEquals(2, count($resultArray));
            $model2->setDeleted();            
            $result = Sancta_Peer_Article::getByEventId($event->getId());
            $result = $result->statusFilter();
            $resultArray = $result->getIterator();
            $this->assertEquals(1, count($resultArray));
            $this->assertEquals(STATUS_ACTIVE, $resultArray[0]->getStatus());
        } 
        
        public function testGetAll() {            
            $model = Sancta_Peer_Article::create(array(
                'content' => TestTools::getRandomString(),
                'title' => TestTools::getRandomString()
            ));         
            $all = Sancta_Peer_Article::getAll();
            $lastCount = count($all->getIterator());
            $model = Sancta_Peer_Article::create(array(
                'content' => TestTools::getRandomString(),
                'title' => TestTools::getRandomString()
            ));           
            $all = Sancta_Peer_Article::getAll();
            $newCount =  count($all->getIterator());
            $this->assertEquals($lastCount+1, $newCount);           
            
        }
 
    }
?>