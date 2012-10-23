<?php
require_once 'SystemController.php';
require_once PATH_LIBS . '/Mindfly/Date.php';
require_once PATH_LIBS . '/Mindfly/Grammar.php';
require_once SANCTA_PATH . '/Article.php';
require_once SANCTA_PATH . '/Event.php';
require_once PATH_BASE . '/models/package/Calendar/Days.php';
/*
 * Контроллер пользователя
 */
class CalendarController extends SystemController {
    
    // стили
    protected $_css = array(
        'settings' => array('calendar.settings.css'),
        'events' => array('calendar.events.css'),
    );
    // скрипты
    protected $_js = array(

    );

    public function  init() {
        parent::init();
        $this->defaultNamespace = new Zend_Session_Namespace('Default');
    }

    /**
     * Меню настроек календаря
     */
    public function settingsAction()    {
        $category = $this->getCategory($this->getRequest()->getParam('id'));
        $this->view->categoryes = MfSystemObjectTable::getTreeArray($category->getChildrens());        
    }

    /**
     * события категории
     */
    public function orthodoxyAction() {
        $this->ortodoxyConfig = new Zend_Config_Ini(CALENDAR_PATH . '/config/ortodoxy.ini');

        // получаем дату из реквеста. Если ее нет - берем текущую
        $date = $this->getRequest()->getParam('date');
        $mindflyData = $date ? new Mindfly_Date($date) : new Mindfly_Date();
        // записываем дату в сессию
        $this->defaultNamespace->selectedDate = $mindflyData;

        
        /**
         * @seo
         * Устанавливаем заголовк
         */
        $this->addTitle('Православный календарь на ' . $mindflyData->getFormatDay());

        // информация о ремарках (постах)
        $fastTable = new MfCalendarOrthodoxyFast();
        $remarkInfo = $fastTable->getPostInfo($mindflyData->getDay());        
        if ($remarkInfo) {            
            $remarkEvent = Sancta_Event::getById($remarkInfo['event_id']);
            $this->view->remarkEvent = $remarkEvent;
        }

        
        // получаем идентификаторы постов и мясоедов, что бы исключить их
        // из карты событий
        $fastsIds = array();
        foreach (explode(',', $this->ortodoxyConfig->event->group) as $fastId) {
            $fastsIds[] = $fastId;
        }

        $eventsInDay = Sancta_Event::getEventListInDay(
            $this->ortodoxyConfig->categoryId,
            $mindflyData->getY(), $mindflyData->getM(), $mindflyData->getD()
        );

        $today = new Mindfly_Date();
        $nextEvents = Sancta_Event::getNextEvents(
            $this->ortodoxyConfig->categoryId,
            $today->getDay()
        );
        $prevEvents = Sancta_Event::getPrevEvents(
            $this->ortodoxyConfig->categoryId,
            $today->getDay()
        );

        $this->view->eventsInDay = $eventsInDay;
        $this->view->nextEvents = $nextEvents;
        $this->view->prevEvents = $prevEvents;
        $this->view->data = $mindflyData;
        
        
        $this->view->remarkInfo = $remarkInfo;

    }





}