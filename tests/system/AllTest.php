<?php
require_once realpath(dirname(__FILE__).'/../') . '/../config/init.php';
require_once PATH_TESTS . '/init.php';
require_once realpath(dirname(__FILE__)) . '/model/ObjectTableTest.php';
require_once realpath(dirname(__FILE__)) . '/model/ObjectRowTest.php';
require_once realpath(dirname(__FILE__)) . '/model/TextRowTest.php';
require_once realpath(dirname(__FILE__)) . '/model/MailTableTest.php';
require_once realpath(dirname(__FILE__)) . '/model/MailTemplateTableTest.php';
require_once realpath(dirname(__FILE__)) . '/model/MailTemplateRowTest.php';


class System_AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Calendar');
        // тесты моделей
        $suite->addTestSuite('ObjectTableTest');
        $suite->addTestSuite('ObjectRowTest');
        $suite->addTestSuite('TextRowTest');        
        $suite->addTestSuite('MailTableTest');
        $suite->addTestSuite('MailTemplateTableTest');
        $suite->addTestSuite('MailTemplateRowTest');
        return $suite;
    }
}
?>