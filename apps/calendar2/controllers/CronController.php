<?php
require_once SANCTA_PATH . '/Peer/User.php';
require_once PATH_LIBS . '/Mindfly/Date.php';
require_once SANCTA_PATH . '/Day/Orthodoxy.php';
require_once SANCTA_PATH . '/Peer/Mail.php';
 
class CronController extends Zend_Controller_Action
{
    public function init() 
    {
         parent::init();  
         setlocale(LC_NUMERIC, 'C');
         $this->_helper->layout()->disableLayout();
         $this->_helper->viewRenderer->setNoRender(true);
    }


    /**
     * отправка email рассылки
     */
    public function deliveryAction() 
    {
        $config = new Zend_Config_Ini(PATH_BASE . '/config/config.ini');
        $gmtActual = $config->time->timeDelivery + $config->time->moscowGmt - date('H');  
        $users = Sancta_Peer_User::getUsersByGmtAndHaveAnySubscribe($gmtActual);
        $userCount = $users->getCount();
        $subscribeId = 1;
        if ($userCount) {
            /**
             * дата для которой требуется сформировать расписание
             */
            $dateOfRasp = new Mindfly_Date();
            if ($gmtActual >= $config->time->timeDelivery + $config->time->moscowGmt) {
                /**
                 * у нас еще прошлый день а у них наступил завтрашний
                 * тогда брать день следущий от текущиего и для него формировать расписание
                 */
                $dateOfRasp = $dateOfRasp->getNextDay();
            } else if ($gmtActual < $config->time->moscowGmt - (12 - $config->time->timeDelivery)) {
                /**
                 * у нас уже наступил завтрашний день, а у них еще предыдущий
                 * брать день предыдущий от текущего и для него формировать расписание
                 */
                $dateOfRasp = $dateOfRasp->getPrevDay();
            }
            $orthodoxyDay = new Sancta_Day_Orthodoxy($dateOfRasp);
            $application = new Zend_Application('production', CALENDAR2_PATH . '/config/application.ini');
            $view = new Zend_View();        
            $view->assign(array(
                'notes'               => $orthodoxyDay->getToDoNotes(),
                'events'              => $orthodoxyDay->getMainEvents(),
                'saintDayEvent'       => $orthodoxyDay->getDayOfSaint(),
                'informationEvents'   => $orthodoxyDay->getNotMainEvents(),
                'articlesForEveryDay' => $orthodoxyDay->getArticleForEveryDay(),
                'date'                => $dateOfRasp
            ));        
            $view->addScriptPath(CALENDAR2_PATH . '/views/scripts/'); 
            $mailText = $view->render('calendar/elements/rss.phtml');
            foreach ($users as $user) {            
                $subribes = $user->getSubsribes();             
                /**
                 * если у них нет подписок
                 */
                if (empty($subribes)) {
                    continue;
                }
                /**
                 * если для данной категории рассылка уже была
                 */                
                if ($user->isDeliveryAlreadySend($subscribeId, $dateOfRasp->getDay())) {
                    continue;
                }
                Sancta_Peer_Mail::addMailToStack($user->getLogin(),
                    'События на '.$dateOfRasp->getFormatDay(), $mailText
                );
                $user->setDeliveryDone($subscribeId, $dateOfRasp->getDay());
            }
         }
    }
    
    /**
     * задание отправить письма, ожидающие отправки
     */
    public function mailSendAction()
    {
        Sancta_Peer_Mail::sendMail();
    }
    
    /**
     * перегрузить фуцнции нуждаютщиеся в перезагрузке
     */
    public function reloadSmartFunctionAction() 
    {
        Sancta_Bundle_SmartFunction::createSmartFunctionDataMap();
    }
}

