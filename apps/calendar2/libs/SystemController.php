<?php
require_once PATH_LIBS . '/plugins/autoload.php';
require_once PATH_LIBS . '/Mindfly/Date.php';
require_once SANCTA_PATH . '/Peer/Template.php';
require_once PATH_LIBS . '/Mindfly/Controller/Action.php';
require_once SANCTA_PATH . '/Peer/User.php';
require_once SANCTA_PATH . '/Peer/Event.php';


use OrthodoxyClient\Api as SanctaApi;


class SystemController extends Mindfly_Controller_Action {

    // авторизированный пользователь
    protected $user = false;
    protected $flash;

    const LINK_USALY = 0;
    const LINK_AUTORIZED = 1;
    const LINK_NOT_AUTORIZED = 2;

    const IN_ABOUT = 1;
    const IN_HEADER = 2;
    const CALENDAR = 2;

    /**
     * текущая дата.
     * @var <type>
     */
    protected $mindflyDate;

    /**
     * сессия
     *
     * @var type
     */
    protected $defaultNamespace;


    /**
     * получаем следущие и предыдущие события для категории
     * @param <type> $categoryId
     */
    public function getPrevNextEvent($categoryId) {
        $dateToday = new Mindfly_Date();
        $nextEvents = Sancta_Peer_Event::getNextEvents($categoryId, $dateToday);
        $prevEvents = Sancta_Peer_Event::getPrevEvents($categoryId, $dateToday);
        $this->view->nextEvents = $nextEvents;
        $this->view->prevEvents = $prevEvents;
    }

    /**
     * получаем дату.
     * Либо из параметров, либо сегодняшнюю
     */
    public function getDate() {
        $date = $this->getRequest()->getParam('date');
        $mindflyData = $date ? new Mindfly_Date($date) : new Mindfly_Date();
        $this->view->date = $mindflyData;
        return $mindflyData;
    }


    public function  init() {
        parent::init();

        $this->defaultNamespace = new Zend_Session_Namespace('Default');
        // заголовок
        $this->view->headTitle()->setSeparator(' | ');
        $this->addJsFile('jquery-1.7.2.min.js');
        $this->flash = $this->_helper->getHelper('flashMessenger');
        /**
         * получаем текущую дату
         */
        $this->mindflyDate = $this->getDate();

        $this->api = new SanctaApi(
            Config_Interface::get('api', 'url')
        );

        /**
         * подгружаем информацию о текущих и следующих событиях
         * которые отображаем в футере
         */
        $this->getPrevNextEvent(Config_Interface::get('orthodoxy', 'category'));
    }


    public function preDispatch() {
        parent::preDispatch();
        if ($this->flash->hasMessages()) {
            $this->view->messages = $this->flash->getMessages();
        }
        // если запрос идет с авторизацией

    }
}