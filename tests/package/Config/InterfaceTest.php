<?php
require_once realpath(dirname(__FILE__).'/../../../') . '/config/init.php';
require_once PATH_TESTS . '/init.php';

require_once PATH_PACKAGE . '/Config/Interface.php';

/**
 *
 * @author ravall
 */
class InterfaceTest extends PHPUnit_Framework_TestCase { 
    public function testEnvironment() {
        $this->assertEquals('', Config_Interface::getEnvironment());
        Config_Interface::setEnvironmentPart($env = 'xxx');
        $this->assertEquals($env, Config_Interface::getEnvironment());
        Config_Interface::setEnvironmentPart($env2 = 'yyy');
        $this->assertEquals($env . '/' . $env2, Config_Interface::getEnvironment());
        Config_Interface::setEnvironment($env3 = 'zzz');
        $this->assertEquals($env3, Config_Interface::getEnvironment());
    }
    
    public function testGet() {
        Config_Interface::setEnvironment('fake');
        $this->assertEquals('xxx', Config_Interface::get('x', 'test_interface'));
        $this->assertEquals('yyy', Config_Interface::get('y', 'test_interface'));
        Config_Interface::setEnvironmentPart('testing');
        $this->assertEquals('xxx', Config_Interface::get('x', 'test_interface'));
        $this->assertEquals('zzz', Config_Interface::get('y', 'test_interface'));
        $this->assertEquals('xyz', Config_Interface::get('z', 'test_interface', 'section'));
    }
    

}
?>