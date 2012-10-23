<?php
require_once 'SystemController.php';
require_once PATH_BASE . '/models/Forms/Calendar/Registration.php';
require_once PATH_BASE . '/models/Forms/Calendar/Authorization.php';
require_once PATH_BASE . '/models/Forms/Calendar/FogetPassword.php';
require_once CALENDAR_PATH . '/model/Form/UserSettings.php';
require_once CALENDAR_PATH . '/model/Form/Authorization.php';

require_once PATH_BASE . '/models/package/Sancta/Mail.php';


/*
 * Контроллер пользователя
 */
class UserController extends SystemController {

    const MODE_AUTH = '#login';
    const MODE_REG = '#registration';
    const MODE_FOGET = '#foget';

    // стили
    protected $_css = array(           
        'settings' => array('user.settings.css', 'date_input.css'),
        'calendar' => array('user.calendar.css')
    );
    // скрипты
    protected $_js = array(
        'login' => array('user.login.js'),
        'registration' => array('user.login.js'),
        'foget' => array('user.login.js'),
        'settings' => array('user.settings.js','jquery.date_input.js'),
        //'calendar' => array('jquery.dropshadow.js','user.calendar.js')
    );

    public function  init() {
        //если AJAX - отключаем авторендеринг шаблонов
        parent::init();
        $this->addTitle('Православный календарь');    
    }
 

    /**
     * выход из системы
     */
    public function logoutAction() {
        $this->getUser();
        $this->user->logout();
        $this->_helper->redirector->gotoRoute(array(), 'home');
    }

    public function registrationAction() {
        // если пользователь авторизирован -
        // то перебрасываем его на основную страницу
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector->gotoRoute(array(), 'home');
        }
        $formRegistration = new Form_Calendar_Registration();
        $formRegistration->setDefault('mode', self::MODE_REG);    
        if ($this->getRequest()->isPost() && $formRegistration->isValid($_POST)) {
            // успешно отвалидировано. регистриуем
            $user = Sancta_User::create(array(
                'login' => $login = $formRegistration->getValue('reg_email'),
                'pass'  => $password = $formRegistration->getValue('password')
            ));
            $text = Calendar_Template::getByName('email_user_registration');
            Sancta_Mail::addMailToStack($login, 'Регистрация на сайте Sancta.ru', $text);

            // и авторизируемся с этими данными
            if (Sancta_User::login($login, $password)) {
                $this->_helper->redirector->gotoRoute(array(), 'home');
            }
        }        
        $this->view->registrationForm = $formRegistration;
       
    }

    public function fogetAction() {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector->gotoRoute(array(), 'home');
        }       
        $formFogetPassword = new Form_Calendar_FogetPassword();
        $formFogetPassword->setDefault('mode', self::MODE_FOGET);        
        if ($this->getRequest()->isPost() && $formFogetPassword->isValid($_POST)) {
            $toEmail = $formFogetPassword->getValue('foget_email');
            if ($user = Sancta_User::getByLogin($toEmail)) {
                $this->flash->addMessage('ссылка для входа в систему была отправлена вам на почту');
                // генерируем hash и отправляем полученную ссылку на почту
                $text = Calendar_Template::getByName('email_fogot_password', array(
                    '%hash%' => $user->createHash(),
                    '%id%' => $user->getId()
                ));
                Sancta_Mail::addMailToStack($toEmail, 'Восстановление пароля. Sancta.ru', $text);
                
                $this->_helper->redirector->gotoRoute(array(), 'login');
             } else {
                 $this->flash->addMessage('вы не зарегистрированы в системе');
                 $this->_helper->redirector->gotoRoute(array(), 'foget');
             }
        }        
        $this->view->fogetForm = $formFogetPassword;        
    }


    public function loginAction() {
        $formAutorization = new Form_Calendar_Authorization();

        if ($this->getRequest()->isPost() && $formAutorization->isValid($_POST)) {           
            if (Sancta_User::login(
                $formAutorization->getValue('auth_email'),
                $formAutorization->getValue('password'))
            ) {
                $this->_helper->redirector->gotoRoute(array(), 'home');
            } else  {
                $formAutorization->getElement('auth_email')->addError('Вы не зарегистрированны в системе');
            }
        }        
        $this->view->autoriztaionForm = $formAutorization;
        
     
    }

    /**
     * Меню настроек пользователя
     */
    public function settingsAction() {
        $this->getUser();
        $form = new Form_Calendar_UserSettings();
        
        $form->setUser($this->user);
        if ($this->getRequest()->isPost()) {
            $this->ortodoxyConfig = new Zend_Config_Ini(
                CALENDAR_PATH . '/config/ortodoxy.ini'
            );

            // заполняем селекты. Для валидации
            $form->setCountryId($_POST['country']);
            $form->setRegionId($_POST['region']);
            if ($form->isValid($_POST)) {               
                $values = $form->getValues();
                $changes = array(
                    'email' => $values['email'],
                    'birthday' => ($values['year'] ? $values['year'] : '0000') . '-' . $values['month'] . '-' . $values['day'],
                    'gmt' => $values['gmt'],
                    'country_id' => $values['country']  ? $values['country'] : null,
                    'region_id' => $values['region'] ? $values['region'] : null,
                    'city_id' => $values['city'] ? $values['city'] : null,
                    'mycity' => $values['mycity'] ? $values['mycity'] : null
                );
                
                $values['isorthodoxy'] ? 
                    $this->user->subscribe($this->ortodoxyConfig->categoryId)
                  : $this->user->unSubscribe($this->ortodoxyConfig->categoryId);
                                
                if (isset($values['pass']) && $values['pass']) {
                    $changes['pass'] = md5($values['pass']);
                    $this->flash->addMessage('Пароль изменен');
                }
                $this->user->update($changes);
                $this->flash->addMessage('Данные сохранены');
                $this->_redirect('user/settings');
            }  
        }        
        $this->view->userForm = $form;
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
        $this->view->form = $form;
        $this->setLayout('ajax');    
    }

    public function loadcitiesAction() {
        $form = new Form_Calendar_UserSettings();
        $form->setRegionId((int) $_POST['region']);
        $this->view->form = $form;
        $this->setLayout('ajax');
    }

    
  
}
?>