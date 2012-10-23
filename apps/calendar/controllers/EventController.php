<?php
require_once 'SystemController.php';
require_once PATH_LIBS . '/Mindfly/Date.php';
require_once PATH_LIBS . '/Mindfly/Grammar.php';
require_once SANCTA_PATH . '/Article.php';
require_once SANCTA_PATH . '/Event.php';
require_once PATH_BASE . '/models/package/Calendar/Days.php';

/*
 * Контроллер событие
 */
class EventController extends SystemController {
    // скрипты
    protected $_js = array(
        'allorthodoxy' => array('autocolumn.min.js')
    );

    public function  init() {
        parent::init();
        $this->defaultNamespace = new Zend_Session_Namespace('Default');
    }

    public function orthodoxyAction() {
        $this->ortodoxyConfig = new Zend_Config_Ini(
            CALENDAR_PATH . '/config/ortodoxy.ini'
        );

        

        $date = $this->defaultNamespace->selectedDate ? $this->defaultNamespace->selectedDate : new Mindfly_Date();
        $eventId = $this->getRequest()->getParam('id');
        
        $event = Sancta_Event::getById($eventId);
        /**
         * @seo
         * Устанавливаем заголовк
         */
        $this->addTitle($event->getTitle() . ' ' . $date->getY() . '. Православный календарь.');
        /**
         * получаем дополнительные статьи
         */        
        $articles = Sancta_Article::getByEventId($eventId);

        $this->view->event = $event;
        $this->view->articles = $articles;
        $this->view->date = $date;
        
        $this->view->config =  $this->ortodoxyConfig;
        
    }

    public function articleAction() {
        /**
         * идентификатор статьи из параметров
         */
        $id = $this->getRequest()->getParam('id');
        /**
         * идентификатор события из параметров
         */
        $eventId = $this->getRequest()->getParam('event');
        /**
         * дата из параметров
         * и
         */
        $date = $this->defaultNamespace->selectedDate ? $this->defaultNamespace->selectedDate : new Mindfly_Date();
        $article = Sancta_Article::getById($id);
         /**
         * @seo
         * Устанавливаем заголовк
         */
        $this->addTitle($article->getTitle() . ' Православный календарь.');
        /**
         * получаем статью
         */        
        $event = Sancta_Event::getById($eventId);
        /**
         * получаем дополнительные статьи
         */
        $articles = Sancta_Article::getByEventId($eventId);
        $this->view->article = $article;
        $this->view->articles = $articles;
        $this->view->date = $date;
        $this->view->event = $event;
    }

    public function allorthodoxyAction() {
        /**
         * @seo
         * Устанавливаем заголовк
         */
        $this->addTitle('Прошедшие события. Православный календарь.');
        $this->ortodoxyConfig = new Zend_Config_Ini(
            CALENDAR_PATH . '/config/ortodoxy.ini'
        );
        // считаем от текущей даты
        $date = new Mindfly_Date();
        $eventList = Sancta_Event::getPrevEventsForYear($this->ortodoxyConfig->categoryId, $date->getDay());
        
        $this->view->events = $eventList;
        $this->view->date = $date;

        
    }
}
