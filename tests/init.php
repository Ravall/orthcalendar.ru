<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once PATH_TESTS . '/lib/TestTools.php';
require_once PATH_TESTS . '/lib/TestCaseSimple.php';


require_once PATH_LIBS_ZEND . '/Zend/Db.php';
require_once PATH_LIBS_ZEND . '/Zend/Db/Table.php';
require_once PATH_LIBS_ZEND . '/Zend/Db/Table/Abstract.php';
require_once PATH_LIBS_ZEND . '/Zend/Db/Table/Row/Abstract.php';
 require_once PATH_LIBS_ZEND . '/Zend/Config/Ini.php';
 
 $applicationIni = new Zend_Config_Ini(CALENDAR2_PATH . '/config/application.ini','testing');
 $db = Zend_Db::factory($applicationIni->resources->db);
 Zend_Db_Table::setDefaultAdapter($db);
 
require_once dirname(__FILE__) . '/env.php';

?>
