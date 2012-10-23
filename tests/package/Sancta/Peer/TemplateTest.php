<?php
    require_once dirname(__FILE__) . '/../SanctaTestCase.php';            
    require_once SANCTA_PATH . '/Peer/Template.php';
    require_once SANCTA_PATH . '/Template.php';
    
    class Sancta_Peer_TemplateTest extends SanctaTestCase {
        
        public function testCreate() {
            $model = Sancta_Peer_Template::create();
            $result = $this->db->fetchRow(
                'select * from mf_system_template where id = ' . $model->getId()
            );
            $this->assertTrue(is_array($result));         
        }
        
        public function testGetById() {
            $model = Sancta_Peer_Template::create(array(               
                'content' => $content = TestTools::getRandomString(),
                'title' => $title =  TestTools::getRandomString()
            ));
            $this->assertEquals($model, Sancta_Peer_Template::getById($model->getId()));            
        }
        
        public function testGetAll() {            
            $model = Sancta_Peer_Template::create(array(
                'content' => TestTools::getRandomString(),
                'title' => TestTools::getRandomString()
            ));         
            $all = Sancta_Peer_Template::getAll(new Sancta_Bundle_StatusesParam(array(STATUS_ACTIVE, STATUS_PAUSE)));
            $lastCount = count($all->getIterator());
            $model = Sancta_Peer_Template::create(array(
                'content' => TestTools::getRandomString(),
                'title' => TestTools::getRandomString()
            ));           
            $all = Sancta_Peer_Template::getAll();
            $newCount =  count($all->getIterator());
            $this->assertEquals($lastCount+1, $newCount);
           
            $all = Sancta_Peer_Template::getAll();
            $count =  count($all->getIterator());
            $this->assertEquals($count, $newCount);

            
            $all = Sancta_Peer_Template::getAll($statuses);
            $count =  count($all);
            $all = Sancta_Peer_Template::getAll($statuses);            
            $reCount =  count($all);            
            $this->assertEquals($count, $reCount);
        }
    }