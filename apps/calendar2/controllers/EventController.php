<?php
require_once 'SystemController.php';
require_once PATH_LIBS . '/Mindfly/Grammar.php';
require_once SANCTA_PATH . '/Peer/Article.php';
require_once PATH_BASE . '/models/package/Calendar/Days.php';
require_once SANCTA_PATH . '/Day/Orthodoxy.php';


/*
 * Контроллер событие
 */
class EventController extends SystemController {
    /**
     * контроллер "православное событие"
     */
    public function orthodoxyAction()
    {
        $eventIdRow = $this->getRequest()->getParam('id');
        if (!$eventId = (int) $eventIdRow ) {
            throw new Exception("event_id must be int. eventId = '{$eventIdRow}'");
        }
        $event = Sancta_Peer_Event::getById($eventId);
        /**
         * @seo
         * Устанавливаем заголовк
         */
        $this->addTitle($event->getTitle() . ' ' . $this->mindflyDate->getY() . '. Православный календарь.');
        $this->addJsFile('lightbox/js/lightbox.js');
        $this->addCssFile('lightbox/css/lightbox.css');
        /**
         * получаем дополнительные статьи
         */
        $articles = Sancta_Peer_Article::getByEventId($eventId);
        $eventInfo = $this->api->getEventInfo($eventId);
        /**
         * view
         */
        $this->view->everyDay = Sancta_Peer_Event::getById(Config_Interface::get('everydayId', 'events'));
        $this->view->event = $event;
        $this->view->icons = ($eventInfo && isset($eventInfo->icons)) ? $eventInfo->icons : False;
        $this->view->articles = $articles;
    }

    public function iconsAction()
    {
        # получаем иконы по api по названию события
        $eventInfo = $this->api->getEventInfo(
            $param = $this->getRequest()->getParam('event_name')
        );
        if (!$eventInfo) {
            throw new Exception(
                "Не удалось получить событие по параметру {$param}"
            );
        }
        $this->addTitle(
            "{$eventInfo->text->title} иконы. Православный календарь"
        );
        $this->addJsFile('lightbox/js/lightbox.js');
        $this->addCssFile('lightbox/css/lightbox.css');
        $this->view->articles = Sancta_Peer_Article::getByEventId(
            $eventInfo->id
        );
        $this->view->eventInfo = $eventInfo;
    }

    /**
     * метод загружает во вью - все статьи на каждый день и события
     *
     * @param Zend_View $view
     */
    private function _loadAllEvents(Zend_View $view)
    {
         $events = Sancta_Peer_Event::getEventListInYear(
             Config_Interface::get('orthodoxy', 'category'),
             $this->mindflyDate
         );
         /**
          * заметки на каждый день - это статьи привязанные к событию "каждый день"
          */
         $articlesForEveryDay = Sancta_Peer_Article::getByEventId(
             Config_Interface::get('everydayId', 'events')
         );
         /**
         * view
         */
         $view->events = $events;
         $view->articlesForEveryDay = $articlesForEveryDay;
         return array('events'              => $events,
                      'articlesForEveryDay' => $articlesForEveryDay);
    }


    /**
     * карта сайта.
     */
    public function allorthodoxyAction()
    {
         /**
          * @seo
          * Устанавливаем заголовк
          */
         $this->addTitle('Православный календарь на ' . $this->mindflyDate->getY());
         $this->_loadAllEvents($this->view);

    }
}
