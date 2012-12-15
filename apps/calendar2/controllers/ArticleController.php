<?php
require_once 'SystemController.php';
require_once PATH_LIBS . '/Mindfly/Date.php';
require_once PATH_LIBS . '/Mindfly/Grammar.php';
require_once SANCTA_PATH . '/Peer/Article.php';
require_once SANCTA_PATH . '/Peer/Event.php';
require_once PATH_BASE . '/models/package/Calendar/Days.php';
require_once SANCTA_PATH . '/Api.php';

/*
 * Контроллер событие
 */
class ArticleController extends SystemController {
    public function orthodoxyAction() {

        $date = $this->getDate();
        if (!$articleId = $this->getRequest()->getParam('id')) {
            throw new Exception('Article id not find');
        }
        $eventId =  $this->getRequest()->getParam('event_id');

        $article = Sancta_Peer_Article::getById($articleId);        
        $this->getPrevNextEvent(Config_Interface::get('orthodoxy','category'));
        #получим информацию о событии через апи
        $eventInfo = Sancta_Api::getEventInfo($eventId);
        /**
         * @seo
         * Устанавливаем заголовк
         */
        $this->addTitle($article->getTitle() . '. Православный календарь.');        
        /**
         * view
         */
        $this->view->icons = $eventInfo ? $eventInfo->icons : False;
        $this->view->event = $eventId ? Sancta_Peer_Event::getById($eventId) : false;
        $this->view->article = $article;
        $this->view->cardShow =  !$article->isRelateTo(Config_Interface::get('everydayId', 'events'));
        $this->view->everyDay = Sancta_Peer_Event::getById(Config_Interface::get('everydayId', 'events'));
    }
}