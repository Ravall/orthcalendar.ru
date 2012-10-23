<?php
require_once dirname(__FILE__) . '/SanctaTestCase.php';
require_once dirname(__FILE__) . '/ObjectSuite.php';
require_once SANCTA_PATH . '/Remark.php';
require_once SANCTA_PATH . '/Event.php';
require_once SANCTA_PATH . '/Peer/Remark.php';
require_once SANCTA_PATH . '/Peer/Event.php';

    
class Sancta_RemarkTest extends ObjectSuite { //SanctaTestCase {    
    protected $className = 'Sancta_Remark';
    protected $classPeerName = 'Sancta_Peer_Remark';



    /**
     * Тестируем возможности текста
     */
    public function testText() {
        $event = Sancta_Peer_Event::create();
        $model = Sancta_Peer_Remark::create(array(
            'event_id' => $event->getId(),
            'smart_function' => 'xxx'
        ));
        $this->_testText($model);

        $model = Sancta_Peer_Remark::create(array(
            'event_id' => $event->getId(),
            'content' => $content = TestTools::getRandomString(),
            'title' => $title =  TestTools::getRandomString(),
            'smart_function' => 'xxx'
        ));
        $this->_testCreateObjectTextNotNull($model, $content, $title);
        $this->assertEquals(Sancta_Peer_Remark::getById($model->getId()), $model);
    }
    
    /**
     * Проверяем обновление парамеров
     */
    public function testUpdate() {
         $event = Sancta_Peer_Event::create();
         $model = Sancta_Peer_Remark::create(array(
            'event_id' => $eventId = $event->getId(),
            'smart_function' => 'xxx'
         ));
         $update = array(
             'event_id' => 1,
             'smart_function' => $newSmartFunction = TestTools::getRandomString(),
             Sancta_Text::CONTENT => $newContent = TestTools::getRandomString(),
             Sancta_Text::ANNONCE => $newAnnonce = TestTools::getRandomString(),
             Sancta_Text::TITLE => $newTitle = TestTools::getRandomString(),             
         );
         
        
         $model->update($update);
         $this->assertEquals($eventId, $model->getEventId(), 'event id изменился. А не должнен был');
         $this->assertEquals($newSmartFunction, $model->getSmartFunction());
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
    
    
}