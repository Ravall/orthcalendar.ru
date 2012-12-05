<?php
require_once 'SystemController.php';
require_once PATH_LIBS . '/Mindfly/Grammar.php';
require_once SANCTA_PATH . '/Peer/Article.php';
require_once PATH_BASE . '/models/package/Calendar/Days.php';
require_once SANCTA_PATH . '/Api.php';
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
        $eventInfo = Sancta_Api::getEventInfo($eventId);

        
        /**
         * view
         */
        $this->view->everyDay = Sancta_Peer_Event::getById(Config_Interface::get('everydayId', 'events'));
        $this->view->event = $event;
        $this->view->icons = $eventInfo->icons;
        $this->view->articles = $articles;
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

    /**
     * sitemap.xml для поисковых систем
     *
     */
    public function sitemapAction()
    {
        header ('Content-type: text/xml;charset=utf-8');
        $this->setLayout('ajax');
        $result = $this->_loadAllEvents($this->view);
        $events = $result['events'];
        $allArticles = $events->getArticles();
        $this->view->allArticles = $allArticles;
    }
}