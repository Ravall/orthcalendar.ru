<?php
require_once CALENDAR2_PATH . '/model/Form/Authorization.php';
require_once CALENDAR2_PATH . '/model/Form/Registration.php';
require_once CALENDAR2_PATH . '/model/Form/FogetPassword.php';
require_once CALENDAR2_PATH . '/model/Form/UserSettings.php';
require_once CALENDAR2_PATH . '/model/Form/EmailSubscribe.php';

require_once SANCTA_PATH . '/Peer/Mail.php';
require_once 'SystemController.php';
/*
 * Контроллер пользователя
 */
class UserController extends SystemController {

    protected $_js = array(
        'subscription'  => array('user_subscription.js')
    );


    /**
     *
     * православная подписка 82
     *
     */
    public function subscriptionAction() {
        $serverHours = date('H');
        $sendHour = 7; # 7 часов по умолчанию
        $formEmailSubscibe = new Form_Calendar_EmailSubscribe();
        if ($this->getRequest()->isPost() && $formEmailSubscibe->isValid($_POST)) {
            $this->view->successText = 'dsds';
        }
        $this->view->form = $formEmailSubscibe;
        $this->view->serverHours = $serverHours;
        $this->view->sendHour = $sendHour;
    }


}
?>