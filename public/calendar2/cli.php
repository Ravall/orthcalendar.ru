<?php
require_once realpath(dirname(__FILE__).'/../../config/') . '/init.php';
require_once PATH_BASE . '/models/package/Config/Interface.php';

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', CALENDAR2_PATH);

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (get_cfg_var('APPLICATION_ENV') ? get_cfg_var('APPLICATION_ENV') : 'devel'));


// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(PATH_LIBS_ZEND),
    realpath(PATH_LIBS),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/config/application.ini'
);

try {
    $opts = new Zend_Console_Getopt(array('action|a=s' => 'cron action'));
    $opts->parse();
    if (!$opts->action) {
        throw new Zend_Console_Getopt_Exception('action require', $opts->getUsageMessage());
    }

    $application = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/config/application.ini');
    $application->bootstrap();
    $frontController = $application->getBootstrap()->getResource('frontcontroller');
    //Если первоначальная настройка ресурсов не нужна, то можно ограничиться $frontController = Zend_Controller_Front::getInstance();
    $frontController->throwExceptions(true);
    //Особый объект ответа, чтобы не выводились HTTP-заголовки 
    $response = new Zend_Controller_Response_Cli(); 
    //Устанавливаем необходимые контроллер-действие-модуль
    
    $controller = 'cron';
    $request = new Zend_Controller_Request_Simple($opts->action, $controller);
    //Поехали!!! 
    $frontController->getDispatcher()->dispatch($request, $response);

} catch (Zend_Console_Getopt_Exception $e) {
    echo $e->getUsageMessage();
    exit;
} catch (Exception $e) {
    die('Application error: ' . $e->getMessage());
}
