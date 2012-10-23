<?php
require_once realpath(dirname(__FILE__).'/../../../') . '/config/init.php';
require_once PATH_TESTS . '/init.php';

class Validate_RegistrationTest extends Tests_Lib_TestCaseSimple {
    private $db;

    public function  setUp() {
        parent::setUp();
        $this->db = Zend_Registry::get('db');        
    }

    public function providerIsValid() {

        return array(
            array('valery.ravall@gmail.com', false),
            array('eeeeemaaaaaiiiiiillllllll@gmail.ru', false),
            array('e@gmail.ru', false),
            array('e@bk.ru', false),
            array('e<>@bk.ru', 'Некорректный адрес электронной почты'),
            array('e"@bk.ru',  'Некорректный адрес электронной почты'),
            array('e"@bk.ru',  'Некорректный адрес электронной почты'),
        );
    }

    /**
     * @dataProvider providerIsValid
     *
     * @param <type> $email
     * @param <type> $mess
     */
    public function testIsValid($email, $mess) {
        $valid = new Mindfly_Validate_Registration();
        $validActual = $valid->isValid($email);
        if (!$mess) {
            $this->assertTrue($validActual);
        } else {
            $this->assertFalse($validActual);        
            $this->assertEquals($mess,  array_shift($valid->getMessages() ));
        }
    }

    /**
     * Валидируем существующий email
     */
    public function testIsValidExist()
    {
        $userTable = new MfSystemUserTable();
        $email = TestTools::getRandomString() . '@email.com';
        $x = $userTable->register($email, TestTools::getRandomString());        
        $valid = new Mindfly_Validate_Registration();
        $validActual = $valid->isValid($email);
        $this->assertFalse($validActual);
        $mess = $valid->getMessages();        
        $this->assertEquals(
              'Указанный адрес электронной почты уже зарегистрирован в системе',
              array_shift($mess));
    }
}
?>