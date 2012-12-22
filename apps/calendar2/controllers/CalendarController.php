<?php
require_once 'SystemController.php';
require_once SANCTA_PATH . '/Day/Orthodoxy.php';
/*
 * Контроллер пользователя
 */
class CalendarController extends SystemController 
{
    /**
     * устанавилваем правило 'rel' => 'canonical'
     * для страницы с информацией о праздниках
     * индексируется предыдущий, текущий и следующий год,
     * кроме дней, когда нет главных событий - только седмицы
     */
    private function orthodoxyActionCanonical($orthodoxyDay)
    {
        $prevYear = (date('Y') - 1) . '-01-01';
        $nextYear = (date('Y') + 1) . '-12-31';
        $date = $this->mindflyDate->getDay();
        if (
            ($date > $nextYear or $date < $prevYear)
            or (
              $date != date('Y-m-d', time()) 
              and !$orthodoxyDay->getMainEvents()->getCount()
            )
        ) {
            $this->view->headLink(
                array(
                    'rel'  => 'canonical', 
                    'href' => $this->view->url(
                        array('date' => ''), 'orthodoxy'
                    )
                ), 'SET'
            );
        }
    }

    /**
     * события выбранного дня
     */
    public function orthodoxyAction() 
    {
        /**
         * @seo
         * Устанавливаем заголовк
         */
        $this->addTitle(
            'Православный календарь на ' . $this->mindflyDate->getFormatDay()
        );
        $this->addJsFile('lightbox/js/lightbox.js');
        $this->addCssFile('lightbox/css/lightbox.css');
        $orthodoxyDay = new Sancta_Day_Orthodoxy($this->mindflyDate);
        $this->orthodoxyActionCanonical($orthodoxyDay);
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

    /**
     * все иконы дня дня
     */
    public function iconsAction() 
    {
        /**
         * @seo
         * Устанавливаем заголовк
         */
        $this->addTitle(
            'иконы дня на ' . $this->mindflyDate->getFormatDay()
        );
        $this->addJsFile('lightbox/js/lightbox.js');
        $this->addCssFile('lightbox/css/lightbox.css');
        $orthodoxyDay = new Sancta_Day_Orthodoxy($this->mindflyDate);
        $this->view->icons = $orthodoxyDay->getIcons();
        $this->view->articles = $orthodoxyDay->getArticleForEveryDay();
    }

    public function rssAction() 
    {

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