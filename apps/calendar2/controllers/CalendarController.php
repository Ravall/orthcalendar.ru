<?php
require_once 'SystemController.php';
require_once SANCTA_PATH . '/Day/Orthodoxy.php';
/*
 * Контроллер пользователя
 */
class CalendarController extends SystemController {
    
    /**
     * события выбранного дня
     */
    public function orthodoxyAction() {    
        /**
         * @seo
         * Устанавливаем заголовк
         */
        $this->addTitle('Православный календарь на ' . $this->mindflyDate->getFormatDay());
        
        
        $orthodoxyDay = new Sancta_Day_Orthodoxy($this->mindflyDate);
        /**
         * view
         */         
        $this->view->notes = $orthodoxyDay->getToDoNotes();         
        $this->view->events = $orthodoxyDay->getMainEvents(); 
        $this->view->saintDayEvent = $orthodoxyDay->getDayOfSaint();
        $this->view->informationEvents = $orthodoxyDay->getNotMainEvents();         
        $this->view->articlesForEveryDay = $orthodoxyDay->getArticleForEveryDay();

        $this->view->icons = $orthodoxyDay->getIcons();
    }
    
    public function rssAction() {
        
        $rssConfig = new Zend_Config_Ini(CALENDAR_PATH . '/config/rss.ini', 'orthodoxy');        
        $rss = $rssConfig->toArray();
                
        $orthodoxyDay = new Sancta_Day_Orthodoxy($this->mindflyDate);
        
        $view = new Zend_View();        
        $view->assign(array(
            'notes' =>  $orthodoxyDay->getToDoNotes(),
            'events' => $orthodoxyDay->getMainEvents(),
            'saintDayEvent' => $orthodoxyDay->getDayOfSaint(),
            'informationEvents' => $orthodoxyDay->getNotMainEvents(),
            'articlesForEveryDay' => $orthodoxyDay->getArticleForEveryDay(),
            'date' => $this->mindflyDate
        ));        
        $view->addScriptPath(current($this->view->getScriptPaths()));        
        
        $rss['entries'][] = array(
            'title' =>  'Православный календарь за ' . $this->mindflyDate->getFormatDay(),
            'link' =>  Config_Interface::get('calendar', 'url') . '/orthodoxy/' . $this->mindflyDate->getDay(),            
            'description' => 'Православный календарь за ' . $this->mindflyDate->getFormatDay(),
            'content' => $view->render('calendar/elements/rss.phtml'),  
        );
        
        $rssFeedFromArray = Zend_Feed::importArray($rss, 'rss');
        $this->view->feed = $rssFeedFromArray;
        $this->disableLayout();
    }
}
?>