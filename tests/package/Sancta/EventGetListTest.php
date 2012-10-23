<?php
    require_once dirname(__FILE__) . '/SanctaTestCase.php';
    require_once SANCTA_PATH . '/Event.php';
    require_once PATH_BASE . '/models/package/SmartFunction/SmartFunction.php';
    
    /**
     * Работа с моделью Event
     *      
     */
    class EventGetListTest extends SanctaTestCase {
        
        const TEST_YEAR = '2009';
        const TEST_MONTH = '12';
        const TEST_DAY = '01';
        const TEST_EVENT_CATEGORY = 11;
        
        
        public function preapreData() {
            $this->db->query('truncate mf_calendar_net');
            $groups = array(1,2,3,4,5);
            $net = new Db_Mapper_CalendarNet();
            for ($i=0; $i<6; $i++) {
                $events[$i] = $event = Sancta_Event::create(array(
                    'content' => '',
                    'title' => TestTools::getRandomString(),
                    'smart_function' => '',
                    'parent_id' => self::TEST_EVENT_CATEGORY
                ));
                $net->addNet($event->getId(), array(
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
         * 2010-04-04
         * @param <type> $categoryId
         * @param <type> $year
         * @param <type> $month
         * @param <type> $day
         * @return <type>
         */
        public function testNetEvents() {
            
            $this->db->query('truncate mf_calendar_event');
            $this->db->query('truncate mf_calendar_net');
            
            $event1 = Sancta_Event::create(array(
                'content' => '',
                'title' => TestTools::getRandomString(),
                'smart_function' => '{Pascha}',
                'parent_id' => 8
            ));
            
            $event2 = Sancta_Event::create(array(
                'content' => '',
                'title' => TestTools::getRandomString(),
                'smart_function' => '04.04',
                'parent_id' => 8
            )); 
            $event3 = Sancta_Event::create(array(
                'content' => '',
                'title' => TestTools::getRandomString(),
                'smart_function' => '25.03~03.04',
                'parent_id' => 8
            ));
            
          
            

            $event1->loadSmartFunction();            
            $event2->loadSmartFunction();
            $event3->loadSmartFunction();
          

            $arrayActual = array();
            foreach (Sancta_Event::getEventListInMonth(8, '2010','04') as $event) {
                $arrayActual[] = $event->getId();
            }
            $arrayExcepted = array($event3->getId(),$event1->getId(),$event2->getId());
            sort($arrayExcepted);
            sort($arrayActual);
            $this->assertEquals($arrayExcepted, $arrayActual);

            $arrayActual = array();
            foreach (Sancta_Event::getEventListInMonth(8, '2010','03') as $event) {
                $arrayActual[] = $event->getId();
            }
            $arrayExcepted = array($event3->getId());
            sort($arrayExcepted);
            sort($arrayActual);
            $this->assertEquals($arrayExcepted, $arrayActual);

            $arrayActual = array();
            foreach (Sancta_Event::getEventListInMonth(8, '2010','02') as $event) {
                $arrayActual[] = $event->getId();
            }
            $arrayExcepted = array();
            sort($arrayExcepted);
            sort($arrayActual);
            $this->assertEquals($arrayExcepted, $arrayActual);

            $arrayActual = array();
            foreach (Sancta_Event::getEventListInDay(8, '2010', '04', '04') as $event) {
                $arrayActual[] = $event->getId();
            }
            sort($arrayExcepted);
            sort($arrayActual);
            $arrayExcepted = array($event1->getId(),$event2->getId());
            $this->assertEquals($arrayExcepted, $arrayActual);
        }
        
    
        
       
        
    }