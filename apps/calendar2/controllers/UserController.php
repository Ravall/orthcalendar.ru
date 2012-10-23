<?php
require_once CALENDAR2_PATH . '/model/Form/Authorization.php';
require_once CALENDAR2_PATH . '/model/Form/Registration.php';
require_once CALENDAR2_PATH . '/model/Form/FogetPassword.php';
require_once CALENDAR2_PATH . '/model/Form/UserSettings.php';
require_once SANCTA_PATH . '/Peer/Mail.php';
require_once 'SystemController.php';
/*
 * Контроллер пользователя
 */
class UserController extends SystemController {
    
    

    protected $_js = array(
        'settings' => array('detect_timezone.js')
    );
    
    private function mustBeNotAutorized() {
        return $this->isAutorized(false);
    }
    private function mustBeAutorized() {
        return $this->isAutorized(true);
    }    
    private function isAutorized($is) {
        if (Zend_Auth::getInstance()->hasIdentity() !== $is) {
            $this->_helper->redirector->gotoRoute(array(), 'home');
        }            
        return;
    }


    /**
     * авторизация по логину и паролю
     *
     * @param <type> $email
     * @param <type> $password
     * @return <type>
     */
    private static function login($email, $password) {
        $authAdapter = new Zend_Auth_Adapter_DbTable(
            null, 'mf_system_user', 'email', 'pass', 'md5(?)'
        );
        $authAdapter->setIdentity($email)
                    ->setCredential($password);
        if ($authAdapter->authenticate()->isValid()) {
            self::setUser(Sancta_Peer_User::getById($authAdapter->getResultRowObject()->id));
                       
            $session = new Zend_Session_Namespace('Zend_Auth');
            // Установить время действия залогинености
            $session->setExpirationSeconds(Config_Interface::get('rememberMe', 'system'));
            
            Zend_Session::rememberMe();
            
            return true;
        }
        return false;
    }
    
      
   


    /**
     * вход в систему
     */
    public function loginAction() {
        $this->mustBeNotAutorized();
        $formAutorization = new Form_Calendar_Authorization();
        if ($this->getRequest()->isPost() && $formAutorization->isValid($_POST)) {
            if (self::login(
                    $formAutorization->getValue('email'),
                    $formAutorization->getValue('password')
            )) {
                /**
                 * @todo нужно переделать на переход на туже страницу, 
                 *       с которой была осуществлена авторизация.
                 */
                $this->_helper->redirector->gotoRoute(array(), 'home');
            } else {                
                // авторизироваться не удалось
                $this->view->authError = Config_Interface::get('auth_error', 'error_text');
            }
        } 
        /**
         * view
         */
        $this->view->form = $formAutorization;
    }

    
    
    /**
     * выход из системы
     */
    public function logoutAction() {    
        $this->mustBeAutorized();
        Zend_Auth::getInstance()->clearIdentity();
        Zend_Session::forgetMe();
        Zend_Session::expireSessionCookie();    
        $this->_helper->redirector->gotoRoute(array(), 'home');
    }

 

    /**
     * экшен восстановление пароля
     */
    public function fogetAction() {
        $this->mustBeNotAutorized();
        $formFogetPassword = new Form_Calendar_FogetPassword();
        if ($this->getRequest()->isPost() && $formFogetPassword->isValid($_POST)) {
            $user = Sancta_Peer_User::getByLogin($formFogetPassword->getValue('email'));            
            if ($user) {
                $text = Sancta_Peer_Template::getByName('email_fogot_password')->getContent(array(
                    '%hash%' => $user->createHash(),
                    '%id%' => $user->getId()
                ));
                Sancta_Peer_Mail::addMailToStack($user->getLogin(), 'Восстановление пароля. Sancta.ru', $text);                
            }
            /**
             * даже если такого пользователя нет, нужно вывести положительное сообщение
             */
            $this->view->successText = Config_Interface::get('foget_success', 'flash_text');            
        }
        /**
         * view
         */
        $this->view->form = $formFogetPassword;
    }

    public function registrationAction() {
        $this->mustBeNotAutorized();
        $formRegistration = new Form_Calendar_Registration();
        if ($this->getRequest()->isPost() && $formRegistration->isValid($_POST)) {
            Sancta_Peer_User::create(array(
                'login' => $login = $formRegistration->getValue('reg_email'),
                'pass'  => $password = $formRegistration->getValue('reg_password')
            ));
            $text = Sancta_Peer_Template::getByName('email_user_registration')->getContent();
            Sancta_Peer_Mail::addMailToStack($login, 'Регистрация на сайте Sancta.ru', $text);
            if (self::login($login, $password)) {
                $this->_helper->redirector->gotoRoute(array(), 'home');
            }
        }
        /**
         * view
         */
        $this->view->form = $formRegistration;
    }
   

    /**
     * Меню настроек пользователя
     */
    public function settingsAction() { 
        $this->mustBeAutorized();
        
        $form = new Form_Calendar_UserSettings();
       
        $form->setUser($this->getUser());
        if ($this->getRequest()->isPost()) {

            // заполняем селекты. Для валидации
            $form->setCountryId($_POST['country']);
            $form->setRegionId($_POST['region']);
            if ($form->isValid($_POST)) {               
                $values = $form->getValues();
                $changes = array(
                    'email' => $values['email'],
                    'birthday' => ($values['year'] ? $values['year'] : '0000') . '-' . $values['month'] . '-' . $values['day'],
                    'country_id' => $values['country']  ? $values['country'] : null,
                    'region_id' => $values['region'] ? $values['region'] : null,
                    'city_id' => $values['city'] ? $values['city'] : null,
                    'mycity' => $values['mycity'] ? $values['mycity'] : null
                );
                
                $user = $this->getUser();

                $values['isorthodoxy'] ? 
                    $user->subscribe(Config_Interface::get('orthodoxy', 'category'))
                  : $user->unSubscribe(Config_Interface::get('orthodoxy', 'category'));
                                
                if (isset($values['pass']) && $values['pass']) {
                    $changes['pass'] = md5($values['pass']);
                }
                $user->update($changes);
                $user->setGmtRaw($values['gmt']);
                $this->setUser($user);
                $this->_redirect('settings');
            }  
        }        
        $this->view->form = $form;
    }

    /**
     * Меню настроек календаря
     */
    public function calendarAction()    {
        $this->getUser();
    }

    public function loadregionsAction() {        
        $form = new Form_Calendar_UserSettings();
        $form->setCountryId((int) $_POST['country']);
        $form->setUser($this->getUser());

        $this->view->form = $form;
        $this->setLayout('ajax');    
    }

    public function loadcitiesAction() {
        $form = new Form_Calendar_UserSettings();
        $form->setRegionId((int) $_POST['region']);
        $form->setUser($this->getUser());
        $this->view->form = $form;
        $this->setLayout('ajax');
    }

    /**
     * получить авторизированного пользователя
     * @return type 
     */
    public static function getUser() {
        $auth = Zend_Auth::getInstance();
        return $auth->hasIdentity() ? $auth->getStorage()->read() : false;
    }

    /**
     * записываем в хранилище пользователя
     *
     * @param <type> $user
     * @return <type>
     */
    public static function setUser($user) {
         $auth = Zend_Auth::getInstance();
         $auth->getStorage()->write($user);
         return true;
    }
 
    
  
}
?>