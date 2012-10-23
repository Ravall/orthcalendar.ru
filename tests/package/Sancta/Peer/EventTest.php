<?php
    require_once dirname(__FILE__) . '/../SanctaTestCase.php';            
    require_once SANCTA_PATH . '/Peer/Remark.php';
    require_once SANCTA_PATH . '/Peer/Event.php';
    require_once SANCTA_PATH . '/Peer/Article.php';
    require_once SANCTA_PATH . '/Event.php';
    require_once PATH_LIBS . '/Mindfly/Date.php';
    
    class Sancta_Peer_EventTest extends SanctaTestCase {
        
        
        public static function smartFunctionForTestCreate() {
            return array(
                'normal' => array(
                                array('smart_function' => 'xxx'), 'xxx'
                 ),
                'emptyFunction' => array(array(),'')
            );
        }

        /**
         * тест создания события
         * @dataProvider smartFunctionForTestCreate
         * @covers  Sancta_Peer_Event::create
         *          Sancta_Peer_Event::getById
         */
        public function testCreate($param, $expectedFunction) {                    
            $model = Sancta_Peer_Event::create($param);        
            $result = $this->db->fetchRow(
                'select event.*,fn.smart_function from mf_calendar_event event '
               . ' LEFT JOIN mf_calendar_smart_function fn ON fn.id = event.function_id'
               . ' where event.id = ' . $model->getId()
            );
            $this->assertTrue(is_array($result));
            $this->assertEquals($expectedFunction, $result['smart_function']);
            $this->assertEquals(Sancta_Peer_Event::getById($model->getId()), $model);       
        }
        
       /**
         * Тестируем получение события
         * 
         * @covers  Sancta_Event::getSmartFunction
         *          Sancta_Peer_Event::getById
         */
        public function testGetById() {
            $model = Sancta_Peer_Event::create(array(
                'content' => $content = TestTools::getRandomString(),
                'title' => $title = TestTools::getRandomString(),
                'smart_function' => $function = TestTools::getRandomString()
            ));
            $model = Sancta_Peer_Event::getById($model->getId());
            $this->assertEquals($function, $model->getSmartFunction());            
        }
        


        /**
         * тест получения событий по категории
         * 
         * @covers  Sancta_Peer_Event::getByCategoryId
         *          Sancta_Event::relateToCategory
         *          Sancta_Peer_Event::create
         */
        public function testGetByCategoryId() {
            $categoryClass = new MfCalendarCategoryTable();
            /**
             * создаем две категории
             */
            $categoryOne = $categoryClass->create(TestTools::getRandomString(time()));
            $categoryTwo = $categoryClass->create(TestTools::getRandomString(time()));
            
            $modelOne = Sancta_Peer_Event::create(array(
                'content' => $content = TestTools::getRandomString(),
                'title' => $title =  TestTools::getRandomString(),
            ));
            $modelTwo = Sancta_Peer_Event::create(array(
                'content' => $content = TestTools::getRandomString(),
                'title' => $title =  TestTools::getRandomString(),
            ));
            $modelThree = Sancta_Peer_Event::create(array(
                'content' => $content = TestTools::getRandomString(),
                'title' => $title =  TestTools::getRandomString(),
            ));
            /**
             * связываем события с категориями
             */
            $modelOne->relateToCategory($categoryOne->id);
            $modelTwo->relateToCategory($categoryOne->id);
            $modelThree->relateToCategory($categoryTwo->id);

            
            $events = Sancta_Peer_Event::getByCategoryId($categoryOne->id);
            $eventsId = array();
            foreach ($events as $event) {
                $eventsId[] = $event->getId();
            }
            $this->assertEquals($eventsId, array($modelOne->getId(),$modelTwo->getId()));

            $events = Sancta_Peer_Event::getByCategoryId($categoryTwo->id);
            $eventsId = array();
            foreach ($events as $event) {
                $eventsId[] = $event->getId();
            }
            $this->assertEquals($eventsId, array($modelThree->getId()));
            /**
             * создаем еще событие и привязываем его к первой категории
             * здесь должен очистится кеш
             */
            $model4 = Sancta_Peer_Event::create(array(
                'content' => $content = TestTools::getRandomString(),
                'title' => $title =  TestTools::getRandomString(),
            ));
            $model4->relateToCategory($categoryOne->id);
            $events = Sancta_Peer_Event::getByCategoryId($categoryOne->id);
            $eventsId = array();
            foreach ($events as $event) {
                $eventsId[] = $event->getId();
            }
            $this->assertEquals($eventsId, array($modelOne->getId(),$modelTwo->getId(),$model4->getId()));
            $events = Sancta_Peer_Event::getByCategoryId($categoryOne->id);
            $eventsId = array();
            foreach ($events as $event) {
                $eventsId[] = $event->getId();
            }
            $this->assertEquals($eventsId, array($modelOne->getId(),$modelTwo->getId(),$model4->getId()));
        }
        
        /**
         * тестируем получение событий за выбранный день
         */
        public function testGetEventListInDay() {
            // создаем три события, умные фунции двух из них 
            // попадают на выбранный день
            // к двум событиям - одному совпавшему и одному не совпавшему, 
            // добавляем ремарки, умные фунции которых пересекаются 
            // с выбранным днем
            // генерим карту, вызываем метод, получаем два события
            /**
             * Удаляем все что есть в карте
             */
            $this->db->query('delete from mf_calendar_net');
            $this->db->query('delete from mf_calendar_smart_function');
             $categoryClass = new MfCalendarCategoryTable();
             $category = $categoryClass->create(TestTools::getRandomString(time()));             
             $event1 = Sancta_Peer_Event::create(array(
                 'content' => $content = TestTools::getRandomString(),
                 'title' => $title =  TestTools::getRandomString(),
                 'smart_function' => '12.01~14.01',
                 'parent_id' => $category->id
             ));
             $event2 = Sancta_Peer_Event::create(array(
                 'content' => $content = TestTools::getRandomString(),
                 'title' => $title =  TestTools::getRandomString(),
                 'smart_function' => '12.01~14.01',
                 'parent_id' => $category->id
             ));
             $event3 = Sancta_Peer_Event::create(array(
                 'content' => $content = TestTools::getRandomString(),
                 'title' => $title =  TestTools::getRandomString(),
                 'smart_function' => '12.01',
                 'parent_id' => $category->id
             ));
             
             
             $remark1 = Sancta_Peer_Remark::create(array(
                'event_id' => $event1->getId(),
                'smart_function' => '12.01~14.01',
                'priority' => 1
             ));
             
             $remark2 = Sancta_Peer_Remark::create(array(
                'event_id' => $event3->getId(),
                'smart_function' => '12.01~14.01',
                'priority' => 2
             ));
             $config = new Zend_Config_Ini(dirname(__FILE__) . '/EventTest/config.ini','net');
             Sancta_Bundle_SmartFunction::createSmartFunctionDataMap($config);
             $list = Sancta_Peer_Event::getEventListInDay($category->id, new Mindfly_Date('2010-01-13'));                          
             $this->assertEquals(array($event1->getId(), $event2->getId()), $list->getIds());
             
             /**
              * проверяем получение событий в зависимости от переодичности
              */
             $event2->update(array('periodic' => true));
             $list = Sancta_Peer_Event::getEventListInDay($category->id, new Mindfly_Date('2010-01-13'), Sancta_Peer_Event::NOT_PERIODIC);                          
             $this->assertEquals(array($event1->getId()), $list->getIds());
             $list = Sancta_Peer_Event::getEventListInDay($category->id, new Mindfly_Date('2010-01-13'), Sancta_Peer_Event::PERIODIC);                          
             $this->assertEquals(array($event2->getId()), $list->getIds());
             
        }
        
        
        
        private function insertDataForNextPrevEvents($config, $categoryId) {
            $this->db->query('delete from mf_calendar_net');
            $this->db->query('delete from mf_calendar_smart_function');
            for ($i=0; $i<5; $i++) {
                $event = Sancta_Peer_Event::create(array(
                    'content' => $content = TestTools::getRandomString(),
                    'title' => $title =  TestTools::getRandomString(),
                    'smart_function' => (12+$i).'.01',
                    'parent_id' => $categoryId
                ));
                $ids[] = $event->getId();
            }
            /**
             * добавим растянутое событие. 
             * часть которого попадает на сегодня, а значит в следующих не должен отобразиться
             */
            $event = Sancta_Peer_Event::create(array(
                'content' => $content = TestTools::getRandomString(),
                'title' => $title =  TestTools::getRandomString(),
                'smart_function' => '11.01~20.01',
                'parent_id' => $categoryId
            ));
            $ids[] = $event->getId();
            
            
            /**
             * добавим события прошлого
             */
            for ($i=0; $i<5; $i++) {
                $event = Sancta_Peer_Event::create(array(
                    'content' => $content = TestTools::getRandomString(),
                    'title' => $title =  TestTools::getRandomString(),
                    'smart_function' => (10-$i).'.01',
                    'parent_id' => $categoryId
                ));
                $ids[] = $event->getId();
            }
            
            Sancta_Bundle_SmartFunction::createSmartFunctionDataMap($config);
            
            return $ids;
        }

        public function providerGetEvents() {
            return array(
                array('getNextEvents', 'testGetNextEventsLessLimit', array(0,1,2,3)),
                array('getNextEvents', 'testGetNextEventsGreaterLimit', array(0,1,2)),
                array('getPrevEvents', 'testGetPrevEventsLessLimit', array(6,7,8,9)),
                array('getPrevEvents', 'testGetPrevEventsGreaterLimit', array(6,7,8))
            );
        }

                
        /**
         * получить список следующих событий 
         * в случае если список на N дней вперед меньше заданного в константе
         * @dataProvider providerGetEvents
         */
        public function testGetNextEventsLessLimit($method, $section, $arrayFlip) {
            // создаем 5 события, первые три попадают в необходимый лимит дней
            // заполняем карту. Устанавливаем  значение 4
            // получаем все 4 события
            $day = new Mindfly_Date('2010-01-11');
            $config = new Zend_Config_Ini(dirname(__FILE__) . '/EventTest/config.ini', $section);
            $expected = $this->insertDataForNextPrevEvents($config, $categoryId = 1);            
            $events = call_user_func('Sancta_Peer_Event::'.$method, $categoryId, $day, $config);                                    
            
            $array = array_flip($arrayFlip);               
            $expected = array_intersect_key($expected, $array);   
            
            $this->assertEquals(array_values($expected), array_values($events->getIds()));
        }
        
        
        
        
        
    }