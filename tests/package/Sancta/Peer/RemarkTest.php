<?php
    require_once dirname(__FILE__) . '/../SanctaTestCase.php';        
    require_once SANCTA_PATH . '/Peer/Remark.php';
    require_once SANCTA_PATH . '/Peer/Event.php';
    require_once SANCTA_PATH . '/Event.php';
    require_once PATH_LIBS . '/Mindfly/Date.php';
    
    class RemarkTest extends SanctaTestCase {
        
        public function testCreate() {        
            $event = Sancta_Peer_Event::create();
            $model = Sancta_Peer_Remark::create(array(
                'event_id' => $event->getId(),
                'smart_function' => $function = 'xxx'
            ));        
            $result = $this->db->fetchRow(
                'select remark.*,fn.smart_function from mf_calendar_remark remark '
               . ' LEFT JOIN mf_calendar_smart_function fn ON fn.id = remark.function_id'
               . ' where remark.id = ' . $model->getId()
            );
            $this->assertTrue(is_array($result));
            $this->assertEquals($function, $result['smart_function']);
            $this->assertEquals(Sancta_Peer_Remark::getById($model->getId()), $model);        
        }
        
        public function testGetAll() {            
            $expectedRemarksIds = $this->prepareRemarks(Sancta_Peer_Event::create());
            $allRemarks = Sancta_Peer_Remark::getAll();               
            $expected = new Sancta_List_Remark($expectedRemarksIds);
            $allRemarksArray = $allRemarks->getIterator();
            $expectedArray =  $expected->getIterator();            
            for ($i=0; $i<2; $i++) {                
                $this->assertEquals($expectedArray[$i]->getEventId(), $allRemarksArray[$i]->getEventId());
                $this->assertEquals($expectedArray[$i]->getPriority(), $allRemarksArray[$i]->getPriority());
            }
            /**
             * добавим в базу, без сброса кеша, что бы убедится что кеш работает.
             */
            $this->db->query('INSERT INTO mf_calendar_remark (event_id) VALUES (3)');
            $allRemarks = Sancta_Peer_Remark::getAll();
            $this->assertEquals(count( $allRemarks->getIterator()), count($expected->getIterator()));
        }
        
        private function prepareRemarks($event) {
            $this->db->query('DELETE FROM mf_calendar_remark');            
            $remark1 = Sancta_Peer_Remark::create(array(
                'event_id' => $event->getId(),
                'smart_function' => $function = 'xxx'
            )); 
            $remark2 = Sancta_Peer_Remark::create(array(
                'event_id' => $event->getId(),
                'smart_function' => $function = 'xxx'
            )); 
            return array($remark1->getId(),$remark2->getId());
            
        }


        public function testGetByEventId() {
            $event = Sancta_Peer_Event::create();
            $expectedRemarksIds = $this->prepareRemarks($event);
            $remarks = Sancta_Peer_Remark::getByEventId($event->getId());
            $remarksArray = $remarks->getIterator();            
            $this->assertEquals($remarksArray[0]->getId(), $expectedRemarksIds[0]);
            $this->assertEquals($remarksArray[1]->getId(), $expectedRemarksIds[1]);
        }
        
        
        public function testGetId() {
            $event = Sancta_Peer_Event::create();
            $modelExpected = Sancta_Peer_Remark::create(array(
                'event_id' => $eventId = $event->getId(),
                'smart_function' => 'xxx'
             ));
            $modelActual = Sancta_Peer_Remark::getById($modelExpected->getId());
            $this->assertEquals($modelExpected,$modelActual);
        }
        
        
        /**
         * Тест на функцию получения списка ремарков
         * 
         * @covers  Sancta_Peer_Remark::getRemarksByDay
         *          Sancta_List_Remark::getMaxPriorityRemark
         */
        public function testGetRemarksByDay() {
            $config = new Zend_Config_Ini(dirname(__FILE__) . '/RemarkTest/config.ini','net');
            /**
             * Удаляем все что есть в карте
             */
            $this->db->query('delete from mf_calendar_net');
            $this->db->query('delete from mf_calendar_smart_function');
         
            // допустим существют два ремарка 
            // разного приоритета и одно событие, 
            // карты которых пересекаются в один день 
            
            // для простоты возьмем одинаковую функцию
            $smartFunction = '19.01~21.01';
            // и день просмотра
            $day = '2011-01-20';
            $event = Sancta_Peer_Event::create(array(
                'smart_function' => $smartFunction
            ));
            $remark1 = Sancta_Peer_Remark::create(array(
                'event_id' => $event->getId(),
                'smart_function' => $smartFunction,
                'priority' => 1
            ));
            $remark2 = Sancta_Peer_Remark::create(array(
                'event_id' => $event->getId(),
                'smart_function' => $smartFunction,
                'priority' => 2
            ));
            /**
             * генерим карту
             */
            Sancta_Bundle_SmartFunction::createSmartFunctionDataMap($config);
            $remarkList = Sancta_Peer_Remark::getRemarksByDay(new Mindfly_Date($day));                        
            $this->assertEquals(2, count($remarkList->getIds()));
            $this->assertTrue(in_array($remark1->getId(), $remarkList->getIds()));
            $this->assertTrue(in_array($remark2->getId(), $remarkList->getIds()));
            $this->assertFalse(in_array($event->getId(), $remarkList->getIds()));
            
            
            /**
             * Проверяем метод получение ремарка с наибольшим приоритетом
             */
            $this->assertEquals($remarkList->getMaxPriorityRemark()->getId(), $remark2->getId());
        }
    }