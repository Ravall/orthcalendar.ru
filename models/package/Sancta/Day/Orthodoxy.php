<?php
require_once SANCTA_PATH . '/Article.php';
require_once SANCTA_PATH . '/Event.php';
require_once SANCTA_PATH . '/Peer/Remark.php';
require_once SANCTA_PATH . '/Peer/Event.php';
require_once SANCTA_PATH . '/Peer/Article.php';
require_once PATH_BASE . '/models/package/Calendar/Days.php';

class Sancta_Day_Orthodoxy 
{
    protected $day;
    protected $eventsInDay;
    
    public function __construct($day) 
    {
        $this->day = $day;
        /**
         * получаем список событий за выбранный день
         */
        $eventsInDay = Sancta_Peer_Event::getEventListInDay(
            Config_Interface::get('orthodoxy', 'category'), $this->day           
        );
        $this->eventsInDay = $eventsInDay;
    }
    
    public function getToDoNotes() {
        /*
         * получаем список заметок по выбранному дню
         */
        $toDoNotes = $this->eventsInDay->getToDoNotes();
        /**
         * берем ремарки этого дня, выбираем из них приоритетный 
         * указываем его заголовок и даем ссылку по нему на событие, 
         * к которому привязан ремарк
         */
        $remarksInfo = Sancta_Peer_Remark::getRemarksByDay($this->day);        
        if ($remark = $remarksInfo->getMaxPriorityRemark()) 
        {                
            /**
             * Добавим ремарк в список заметок
             */
             $remark->addToDo($toDoNotes);
        }
        return $toDoNotes;
    }
    
    public function getMainEvents() {
        /**
         * Выбираем не переодические события
         */
        $notPeriodicEvents = $this->eventsInDay->getPeriodicEvents(FALSE);
        /**
         * дни почитания святых
         */
        $categoryOfSaint = Config_Interface::get('saint', 'category');
        /**
         * другие дни
         * убрали дни святых
         */
        $notPeriodicEvents = $notPeriodicEvents->getCategory(array($categoryOfSaint), FALSE);
        return $notPeriodicEvents;
    }
    
    public function getDayOfSaint() {
        /**
         * дни почитания святых
         */
        $notPeriodicEvents = $this->eventsInDay->getPeriodicEvents(FALSE);
        $categoryOfSaint = Config_Interface::get('saint', 'category');
        $saintDayEvent = $notPeriodicEvents->getCategory(array($categoryOfSaint));  
        return $saintDayEvent;
    }
    
    public function getNotMainEvents() {
        /**
         * события которые следует отметить как информационные и не нагружать основной вывод
         */
        $informationEvents = $this->eventsInDay->getCategory(array(
            Config_Interface::get('sedmiza', 'category'),
            Config_Interface::get('fasts', 'category'),
            Config_Interface::get('meatfare', 'category'),
            Config_Interface::get('pop_pred', 'category'),
        ));
        return $informationEvents;
    }
    
    public function getArticleForEveryDay() {
        /**
         * заметки на каждый день - это статьи привязанные к событию "каждый день"
         */
        $articlesForEveryDay = Sancta_Peer_Article::getByEventId(
            Config_Interface::get('everydayId', 'events')
        );
        return $articlesForEveryDay;
    }
    
    public function getRssPost() {
        
        Sancta_Peer_Template::getByName('rss_post')->getContent();
    }
}