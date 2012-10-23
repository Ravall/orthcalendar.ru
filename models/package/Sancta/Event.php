<?php

require_once PATH_BASE . '/models/Db/Mapper/CalendarEvent.php';
require_once PATH_BASE . '/models/Db/Mapper/CalendarNet.php';
require_once PATH_BASE . '/models/Db/Mapper/SystemRelation.php';
require_once PATH_LIBS_ZEND . '/Zend/Config/Ini.php';
require_once PATH_BASE . '/models/package/SmartFunction/SmartFunction.php';
require_once PATH_BASE . '/models/package/Config/Interface.php';

require_once SANCTA_PATH . '/Abstract/System.php';

class Sancta_Event extends Sancta_Abstract_System {
   
    protected $mapperTable = 'Db_Mapper_CalendarEvent';
    
    private $eventId;
    private $function_id;
    private $smartFunction;
    private $periodic;
    /**
     * старое поле нужно удалить по завершению обновления
     * @var type 
     */
    public $oldSmartFunction;

    /**
     * параметры которые нужно обновлять через update
     * @var type 
     */
    protected $modelParamKeys = array('periodic','function_id');
    
    protected $cacheOptions = array(
        Sancta_Abstract_Cache::UPDATE_TAG_INDEX => array('SP_Event_list'),
    );
    
    /**
     * отказываемся от хранения родителя события (категорию) в поле parent_id
     * и связываем их через systemRelationTable
     * 
     * @test Sancta_EventTest::testRelated
     * @param <type> $categoryId
     * @return <type>
     */
    public function relateToCategory($categoryId) {        
        $systemRelationTable = new Db_Mapper_SystemRelation();
        $result =  $systemRelationTable->setRelates($this->getId(), array($categoryId));
        return $result;        
    }
    
    /**
     * обновление параметров модели
     * 
     * @param type $params 
     */
    public function update($params) {                
        if (!$this->function_id) {            
            $params['function_id'] = Sancta_Bundle_SmartFunction::create('');            
        }
        $this->smartFunction->update($params);
        parent::update($params);
    }

    public function getParentIds() {
        return $this->getRelatedObjets();
    }

    protected function _setModel($model) {
        $this->eventId = $model->id;
        $this->function_id = $model->function_id;       
        $this->periodic = $model->periodic;
        /**
         * старое поле. Нужно удалить по завершению обновления
         */
        $this->oldSmartFunction = $model->smart_function;
        $this->smartFunction = new Sancta_Bundle_SmartFunction($this->function_id);
    }
    
    
    /**
     * возвращает флаг переодичности события
     * 
     * @return type 
     */
    public function isPeriodic() {
        return (bool) $this->periodic;
    }
    
    /**
     * возвращает флаг.
     * принадлежит ли событие группе событий почитания святцев
     */
    public function isInCategory($arrayOfCategory) {                
       $categoryId = current($this->getRelatedObjets());       
       if (in_array($categoryId, $arrayOfCategory)) {
           return true;
       }
       return false;   
    }

    /**
     * получить умную функцию
     * 
     * @return type 
     */
    public function getSmartFunction() {
        return $this->smartFunction->getSmartFunction();
    }
    
    public function getSmartFunctionId() {
        return $this->function_id;
    }
  

    /**
     * при изменинии статуса на паузу,
     * удаляем все событытия из сетки
     *
     *
     */
    public function  setPaused() {
        $net = new Db_Mapper_CalendarNet();
        $net->removeByEventId($this->getId());
        self::clearNetCache($this->getCategoryId());
        parent::setPaused();
    }

    /**
     * при изменинии статуса на удаленный
     * удаляем все событытия из сетки
     *
     */
    public function  setDeleted() {
        $net = new Db_Mapper_CalendarNet();
        $net->removeByEventId($this->getId());
        self::clearNetCache($this->getCategoryId());
        parent::setDeleted();
    }

    /**
     * при активации события - взводим флаг.
     */
    public function setActived() {
        $this->reload();
        parent::setActived();
    }


    /**
     * Убираем скобки, последнюю точку
     */
    public function getTitle($clear = false) {
        $title =  parent::getTitle();
        if (!$clear) {
            return $title;
        } else {        
            $title = preg_replace('/\(.*?\)/', '', $title);
            $title = str_replace(".", '', $title);
            return trim($title);
        }
    }



     /**
     * @TODO нужен тест
     * @param <type> $categoryId
     * @param <type> $date
     * @return <type>
     */
   # public static function getPrevEventsForYear($categoryId, $date) {
   #     
   #      $cache = self::getCahce();
   #      $cacheIndex = self::NET_PREV_EVENTS_FOR_YEAR_CACHE_INDEX_PREFIX . $categoryId. '_' . str_replace('-', '_', $date);         
   #      if (!$cache->test($cacheIndex)) {
   #          $events = self::_getEventsPrevForYear($categoryId, $date);             
   #          $eventList = $ids = array();
   #          foreach ($events as $event) {
   #              if ($event['event_id'] == 65) continue;
   #              $ids[] = $event['event_id'];
   #              $eventList[$event['year'] . '-' . $event['month']][] = new self($event['event_id']);
   #          }
   #          $cache->save($eventList, $cacheIndex, array_merge($ids, array(self::CACHE_TAG_ALL, self::CAHCE_NET . $categoryId)));
   #      } else {
   #          $eventList = $cache->load($cacheIndex);
   #      }
   #      return $eventList;
   # }
    
    
    
    
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
        $url = $view->url(array('id'=>$this->getId()),  'event');
        if ($text) {
            $link = "<a href='{$url}'>{$text}</a>";
        }   else {
            $link = $url;
        }  
        return $link;
    }
    
    
    /**
     * получаем объект ремарк для данного события
     * @return Sancta_Peer_Remark 
     */
    public function getRemarkObject() {
        return Sancta_Peer_Remark::getByEventId($this->getId());
    }


    /**
     * получить ремарки для данного события
     * @return type 
     */
    public function getRemarks() {
        return $this->getRemarkObject()->getRemarks();
    }
    
    public function getImage($width, $height) {
        $image = parent::getImage($width, $height);
        $everyDayId = Config_Interface::get('everydayId', 'events');
        if (!$image && $this->getId() != $everyDayId) {
            /**
             * если картинка к событию не найдена - берем картинку из события "каждый день"
             */
            $everyDayEvent = Sancta_Peer_Event::getById($everyDayId);
            $image = $everyDayEvent->getImage($width, $height);
        }
        return $image;
    }


    /**
     * получаем описание того как проводить следует этот праздник
     *
     * @param bool $withArcticles = true(выводить ли туду со статьей)
     * @return array
     */
    public function getToDo($withArcticles = true) {
        $result = array();
        if ($annonce = $this->getAnnonce()) {
            $explode = explode(Sancta_List_Event::TODONOTE_SEPARATOR, $annonce);
            foreach ($explode as $link) {
                $link = trim($link);
                if ($link) {
                    $result[] = $this->getLink($link);
                }
            }
        }
        if ($withArcticles) {
            foreach (Sancta_Peer_Article::getByEventId($this->getId()) as $article) {
                $result = array_merge($result, $article->getToDo());                
            }
        }
        return $result;
      }

 
    

}
?>