<?php
require_once realpath(dirname(__FILE__).'/../../config/') . '/init.php';
require_once PATH_BASE . '/models/package/Config/Interface.php';

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', SYSTEM_PATH);

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));



// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(PATH_LIBS_ZEND),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/config/application.ini'
);

$application->bootstrap()
            ->run();
