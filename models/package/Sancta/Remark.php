<?php
/**
 *  Ремарк - это своеобразная информация, привязанная к событию и имеющая свою дату
 *  например 
 *      событие - великий пост, к этому событию привязаны несколько ремарков, имеющие свою дату
 *      ремарк1 - первая неделя поста, ремарк2- вторая неделя поста, ремарк3 - выходные поста
 *      кроме даты, ремарк содержит в себе текстовую информацию, и приоритет, последний нужен для 
 *      определения главного ремарка на выбранный день в случае если в выбранный день присутвуют несколько ремарков
 * 
 * 
 * ремарк всегда один на день. Это своеобразная характеристика дня
 */
require_once SANCTA_PATH . '/Abstract/System.php';

class Sancta_Remark extends Sancta_Abstract_System {
        
    /**
     * параметры которые нужно обновлять через update
     * @var type 
     */
    protected $modelParamKeys = array('priority');
    
    protected $mapperTable = 'Db_Mapper_CalendarRemark';
    
    protected $cacheOptions = array(
        Sancta_Abstract_Cache::UPDATE_TAG_INDEX => array('list', 'Sancta_Peer_Remark'), // сюда нужно будет добавить тег smart_function
    );
    /**
     * умная функция
     * @var type 
     */
    private $smartFunction;
    private $function_id;

    private $eventId;
    private $priority;
    
    protected function _setModel($model) {        
        $this->eventId = $model->event_id;
        $this->priority = $model->priority;
        $this->function_id = $model->function_id;
        $this->smartFunction = new Sancta_Bundle_SmartFunction($this->function_id);
    }
    
    protected function createModelValidate($params) {
        if (!isset($params['event_id'])) {
            throw new Exception('event_id not find');
        }        
    }


    
    public function getEventId() {
        return $this->eventId;
    }

    public function getPriority() {
        return $this->priority;
    }
    
    public function getSmartFunction() {
        return $this->smartFunction->getSmartFunction();
    }
    
    public function getReload() {     
        return $this->smartFunction->getReload();
    }
    
    public function update($params) {        
        $this->smartFunction->update($params);
        parent::update($params);
    }
    
    public function getLink($text = false, $options = array()) {
        $bootstrap = Zend_Controller_Front::getInstance()
                   ->getParam('bootstrap');
        if ($bootstrap === null) {
            $application = new Zend_Application(
                APPLICATION_ENV, APPLICATION_PATH . '/config/application.ini'
            );
            $application->bootstrap();
            $bootstrap = $application->getBootstrap();
        }
        
        $view = $bootstrap->getResource('view');
        $url = $view->url(array('id' => $this->getEventId()),  'event');
        if ($text) {
            $link = "<a href='{$url}'>{$text}</a>";
        }   else {
            $link = $url;
        }  
        return $link;
    }
    
    public function addToDo(&$toDoArray) {
        foreach (explode(Sancta_List_Event::TODONOTE_SEPARATOR, $this->getAnnonce()) as  $todo) {
            $todo = trim($todo);
            if ($todo) {
                $toDoArray[] = $this->getLink($todo);
            }
        }
            
    }
    
}