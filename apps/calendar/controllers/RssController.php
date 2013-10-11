<?php
require_once PATH_LIBS . '/Mindfly/Controller/Action.php';
require_once PATH_LIBS . '/Mindfly/Date.php';
require_once SANCTA_PATH . '/Event.php';
require_once SANCTA_PATH . '/Article.php';
/*
 * Контроллер Rss
 */
class RssController extends Mindfly_Controller_Action {
    public function orthodoxyAction() {
        $rssConfig = new Zend_Config_Ini(CALENDAR_PATH . '/config/rss.ini','orthodoxy');
        $ortodoxyConfig = new Zend_Config_Ini(CALENDAR_PATH . '/config/ortodoxy.ini');
        $rss = $rssConfig->toArray();
        $day = new Mindfly_Date();
        $events = Sancta_Event::getEventListInDay(
                $ortodoxyConfig->categoryId,
                $day->getY(), $day->getM(), $day->getD()
        );
        foreach ($events as $event) {
            if (!in_array($event->getId(),array_values($ortodoxyConfig->event->fast->toArray()))
                && !in_array($event->getId(),array_values($ortodoxyConfig->meatfare->toArray()))
                        )
                {
                    $addArticles = array();
                    $articles = Sancta_Article::getByEventId($event->getId());
                    if ($articles) {
                        foreach ($articles as $article) {
                            $addArticles[] = '<a href="http://orthcalendar.ru/event/article/id/'.$article->getId().'/event/'.$event->getId().'">'.$article->getTitle().'</a>';
                        }
                    }
                    $addArticlesStr = count($addArticles) ? ('<br/><b>Статьи:</b><br/>' . implode('<br/>', $addArticles)) : '';
                    $rss['entries'][] = array(
                        'title' => $event->getTitle(),
                        'link' => 'http://orthcalendar.ru/event/orthodoxy/'.$event->getId(),
                        'description' => strip_tags($event->getContent()),
                        'content' => $event->getContent() . $addArticlesStr,
                    );
                }
        }
        
        $rssFeedFromArray = Zend_Feed::importArray($rss, 'rss');
        $this->view->feed = $rssFeedFromArray;
        $this->disableLayout();
    }
}
