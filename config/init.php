<?php
date_default_timezone_set('Europe/Moscow');

// подключаем константы
require_once dirname(__FILE__) . '/constants.php';
//require_once (PATH_LIBS . '/Rediska/0.5/library/Rediska.php');

define('LANG_DEFAULT' , LANG_RU);

set_include_path(
      get_include_path()
    . PATH_SEPARATOR . PATH_BASE
    . PATH_SEPARATOR . PATH_LIBS_ZEND
    . PATH_SEPARATOR . SYSTEM_PATH . '/model'
    . PATH_SEPARATOR . CALENDAR_PATH . '/model'
);


/**
 * зансоим в реестр переводчик
 * Переводы храним в одном месте.
 * Здесь
 */
require_once 'Zend/Translate.php';
require_once 'Zend/Registry.php';
$translator = new Zend_Translate('array', PATH_BASE.'/languages',LANG_DEFAULT);
$translator->addTranslation(PATH_BASE . '/languages/' . LANG_DEFAULT . '/Zend_Validate.php', LANG_DEFAULT);
Zend_Registry::set('translator', $translator);


// инициализируем модули
require_once SYSTEM_PATH . '/init.php';

require_once CALENDAR_PATH . '/init.php';


/**
 * подключаем переводы сообщений
 */
require_once 'Zend/Validate/Abstract.php';
Zend_Validate_Abstract::setDefaultTranslator($translator);





