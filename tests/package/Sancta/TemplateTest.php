<?php
    require_once dirname(__FILE__) . '/ObjectSuite.php';
    require_once SANCTA_PATH . '/Template.php';

    class Sancta_TemplateTest extends ObjectSuite {
        /**
         * Тестируем возможности текста
         */
        public function testText() {
            $template = Sancta_Peer_Template::create();        
            $this->_testText($template);
            $template = Sancta_Peer_Template::create(array(                
                'content' => $content = TestTools::getRandomString(),
                'title' => $title =  TestTools::getRandomString(),                
            ));
            $this->_testCreateObjectTextNotNull($template, $content, $title);
            $this->assertEquals(Sancta_Peer_Template::getById($template->getId()), $template);
        }

        
        /**
         * Проверяем обновление парамеров
         */
        public function testUpdate() {            
            $model = Sancta_Peer_Template::create();
            $update = array(                
                'smart_function' => $newSmartFunction = TestTools::getRandomString(),
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

      

       
    }

