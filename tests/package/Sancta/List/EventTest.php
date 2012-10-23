<?php
    require_once dirname(__FILE__) . '/../SanctaTestCase.php';        
    require_once SANCTA_PATH . '/Peer/Article.php';
    require_once SANCTA_PATH . '/Peer/Event.php';    
    require_once SANCTA_PATH . '/Event.php';    
    require_once PATH_LIBS . '/Mindfly/Date.php';
        
    class Sancta_List_EventTest extends SanctaTestCase {
        
        const TEST_YEAR = '2009';
        const TEST_MONTH = '12';
        const TEST_DAY = '01';
        const TEST_DATE = '2009-12-01';
        const TEST_EVENT_CATEGORY = 11;
        
        public function testGetToDoNotes() {           
            $eventNote1 = TestTools::getRandomString();
            $eventNote2 = TestTools::getRandomString();
            $article1Note1 = TestTools::getRandomString();
            $article1Note2 = TestTools::getRandomString();
            $article2Note1 = TestTools::getRandomString();
            $article2Note2 = TestTools::getRandomString();
            
            $event1 = Sancta_Peer_Event::create(array(
                'content' => TestTools::getRandomString(),
                'title' => TestTools::getRandomString(),
                Sancta_Text::ANNONCE => $eventNote1 . "\n" . $eventNote2,    
                'parent_id' => 1,
            ));           
            $event2 = Sancta_Peer_Event::create(array(
                'content' => TestTools::getRandomString(),
                'title' => TestTools::getRandomString(),                    
                'parent_id' => 1,
            ));
            $article1 = Sancta_Peer_Article::create(array(
                'content' => TestTools::getRandomString(),
                Sancta_Text::ANNONCE => $article1Note1 . "\n" . $article1Note2,                
                'title' => TestTools::getRandomString()                
            ));
            $article2 = Sancta_Peer_Article::create(array(
                'content' => TestTools::getRandomString(),
                Sancta_Text::ANNONCE => $article2Note1 . "\n" . $article2Note2,                
                'title' => TestTools::getRandomString()
                    
            ));
            $article3 = Sancta_Peer_Article::create(array(
                'content' => TestTools::getRandomString(),                
                'title' => TestTools::getRandomString()
            ));
            $article1->relateToEvent($event1->getId());
            $article2->relateToEvent($event2->getId());
            $article3->relateToEvent($event1->getId());
            
            $eventsList = new Sancta_List_Event(array($event1->getId(),$event2->getId()));
            
            $this->assertEquals(array(
                $event1->getLink($eventNote1),  
                $event1->getLink($eventNote2),
                $article1->getLink($article1Note1),
                $article1->getLink($article1Note2),
                $article2->getLink($article2Note1),
                $article2->getLink($article2Note2),
            ),$eventsList->getToDoNotes());
        }
        
        
        public function preapreData() {
            $this->db->query('truncate mf_calendar_net');
            $groups = array(1,2,3,4,5);
            $net = new Db_Mapper_CalendarNet();
            for ($i=0; $i<6; $i++) {
                $function = Sancta_Bundle_SmartFunction::create('');
                $events[$i] = $event = Sancta_Peer_Event::create($x = array(
                    'content' => '',
                    'title' => TestTools::getRandomString(),                    
                    'parent_id' => self::TEST_EVENT_CATEGORY,
                    'smart_function' => ''
                ));
                
                $net->addNet($event->getSmartFunctionId(), array(
                    array(self::TEST_DAY, self::TEST_MONTH, self::TEST_YEAR))
                );                   
            }
            
            $event = $events[0];
            $event->relateToCategory($groups[0]);
            $exceptGroup0[] = $event->getId();
            
            $event = $events[1];
            $event->relateToCategory($groups[0]);
            $exceptGroup0[] = $event->getId();

            $event = $events[2];                        
            $event->relateToCategory($groups[1]);
            $event = $events[3];
            $event->relateToCategory($groups[2]);
            
            $event = $events[4];
            $event->relateToCategory($groups[3]);
            $exceptGroup3and4[] = $event->getId();
            
            $event = $events[5];
            $event->relateToCategory($groups[4]);
            $exceptGroup3and4[] = $event->getId();
            
            /**
             * Ожидаемые группы
             */
            $this->exceptGroups = array($exceptGroup0,$exceptGroup3and4);
            $this->groups = $groups;
            $this->events = $events;
        }

        /**
         * создаем четыре события e1,e2,e3,e4
         * связываем e1 и e3 c группой g1
         * связываем e2 с группой g2
         * связываем e4 c группой g3
         * 
         * получаем массив по группам g1,g3
         * 
         */
        public function testGroupEvent() {
            $this->preapreData();
            $groups = $this->groups;
            list($exceptGroup0,$exceptGroup3and4) = $this->exceptGroups;
            
            /**
             * перечисляем категории событий, по которым нужно отгруппировать
             */            
            $groupBy = array(
                'group0' => array($groups[0]),
                'group2' => array($groups[2]),
                'group3and4' => array($groups[3], $groups[4])
            );
            /**
             * получаем массив событий с учетом группировки
             */
            $eventList = Sancta_Peer_Event::getEventListInDay(
                self::TEST_EVENT_CATEGORY, new Mindfly_Date(self::TEST_DATE)
            );
            $ids = $eventList->getIds();
            $this->assertFalse(empty($ids), 'данные не были получены');
            
            $eventList = $eventList->groupBy($groupBy);
            
            $this->assertTrue(isset($eventList['group0']),'группа g1 не найдена');
            $this->assertTrue(isset($eventList['group2']),'группа g3 не найдена');
            $this->assertTrue(isset($eventList['group3and4']),'группа group3and4 не найдена');
            $this->assertFalse(isset($eventList['group1']),'группа g2 не должна быть найдена');
            
            $this->assertEquals(2, count($eventList['group0']->getIds()),'количество событий в g1 не соотсветсвует истине');
            $this->assertEquals(2, count($eventList['group3and4']->getIds()),'количество событий в group3and4 не соотсветсвует истине');
            $this->assertEquals(1, count($eventList['group2']->getIds()),'количество событий в g3 не соотсветсвует истине');
            
            $group0Array = $eventList['group0']->getIterator();
            $eventsGroup0 = array($group0Array[0]->getId(),$group0Array[1]->getId());            
            sort($eventsGroup0);
            sort($exceptGroup0);
            $this->assertEquals($exceptGroup0, $eventsGroup0,'неправильное содержимое в найденных группах');
            sort($exceptGroup3and4);
            $group3and4Array = $eventList['group3and4']->getIterator();
            $eventsGroup3and4 = array($group3and4Array[0]->getId(),$group3and4Array[1]->getId());            
            $this->assertEquals($exceptGroup3and4, $eventsGroup3and4,'неправильное содержимое в найденных группах');

        }
    }