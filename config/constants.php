<?php
/**
 * Env
 */
define('ENV_PROD', 'prod');
define('ENV_TEST', 'test');
define('ENV_DEVEL', 'dev');

/**
 * Пути
 **/
define('PATH_BASE', realpath(dirname(__FILE__).'/..'));
define('PATH_LIBS' , PATH_BASE . '/libs');
define('PATH_LIBS_ZEND' , PATH_BASE . '/libs/Zend Framework/1.10.6/library');
define('PATH_TESTS' , PATH_BASE . '/tests');
define('PATH_FIXTURES', PATH_BASE . '/config/fixtures');
define('PATH_PACKAGE', PATH_BASE . '/models/package');
// путь к моделям sancta
define('SANCTA_PATH', PATH_PACKAGE . '/Sancta');

/**
 * logs
 */
define('PATH_LOGS', PATH_BASE . '/logs');
define('PATH_LOGS_SCRIPT', PATH_LOGS . '/script');


/**
 * Языки
 **/
define('LANG_RU', 'ru');
define('LANG_EN', 'en');

/**
 * формат времени внутри системы
 */
define('DATE_SYSTEM_FORMAT_FULL','Y-m-d H:m');
define('DATE_SYSTEM_FORMAT_SHORT','Y-m-d');

/**
 * Модули
 */
// system
define('SYSTEM_PATH', PATH_BASE . '/apps/system');
// calendar
define('CALENDAR_PATH', PATH_BASE . '/apps/calendar');
// calendar2
define('CALENDAR2_PATH', PATH_BASE . '/apps/calendar2');
// admin2
define('ADMIN2_PATH', PATH_BASE . '/apps/admin2');

// common
define('COMMON_PATH', PATH_BASE . '/apps/_common');

