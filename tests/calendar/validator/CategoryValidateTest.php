<?php
require_once realpath(dirname(__FILE__).'/../../../') . '/config/init.php';
require_once PATH_TESTS . '/init.php';
require_once PATH_LIBS_ZEND . '/Zend/View/Helper/Abstract.php';
require_once PATH_LIBS . '/Mindfly/Outputs/TreeMenuOutput.php';

require_once PATH_LIBS_ZEND. '/Zend/Db/Table/Abstract.php';
require_once SYSTEM_PATH.'/model/MfSystemObjectTable.php';
require_once SYSTEM_PATH.'/model/MfSystemTextTable.php';
require_once SYSTEM_PATH.'/model/MfSystemTextRow.php';
require_once SYSTEM_PATH.'/model/MfSystemObjectTextTable.php';

require_once CALENDAR_PATH . '/model/MfCalendarCategoryTable.php';
require_once CALENDAR_PATH . '/model/MfCalendarCategoryRow.php';

/**
 * Тест валидатора создания категории
 *
 * @author user
 */
class CategoryValidateTest extends PHPUnit_Framework_TestCase {
    private $db;

    public function  setUp() {
        parent::setUp();
        $this->db = Zend_Registry::get('db');
        
    }

    /**
     * @dataProvider providerCategoryTitle
     */
    public function testCategoryValidation($title, $result) {
        $valid = new Mindfly_Validate_Calendar_Category();
        $this->assertTrue($valid->isValid($title) === $result);
    }

    public function providerCategoryTitle() {
        return array(
            array('Living with Crazy Buttocks',true),
            array('Пегий пес, бегущий краем моря',true),
            array('Когда падают горы (вечная невеста)',true),
            array('Метро 2033',true),
            array('Windows для чайников за 20 часов',true),
            array('"Прерванная бесконечность"',true),
            array('IT-Биографии',true),
            array('C++',true),
            array('Erlang/OTP',true),
            array('1',false),
            array('a',false),
            array('э',false),
            array('sdfffffffffffffffffffffffffffffffffffffffffffffffffffffffffff',false),
            array('<tag>ssd</tag>',false),
            array('',false),
            array(' ',false),
            array('   ',false),
        );
    }

    /**
     * тест валидатора на исключения какой-либо категории,
     * используется при редактировании
     */
    public function testValidationExistCategory() {
        $titleSome = TestTools::getRandomString(time());
        $calendarCategory = new MfCalendarCategoryTable();
        $calendarCategory->create($titleSome);

        $title = TestTools::getRandomString(time());
        $category = $calendarCategory->create($title);
        $context = array('id' => $category->id);

        $valid = new Mindfly_Validate_Calendar_Category();
        $this->assertFalse($valid->isValid($title),
            'Неправильно отвалидирована существующая категория');
        $this->assertTrue($valid->isValid($title, $context),
            'Неправильно отвалидирована категория с актуальным исключением');
        $this->assertFalse($valid->isValid($titleSome, $context),
            'Неправильно отвалидирована категория с не актуальным исключением');
    }

    /**
     * проверяем правильность вывода шаблона об ошибке
     *
     * @dataProvider providerInvalidTitle
     */
    public function testValidatorMessageInvalid($title, $errorMess) {
        $valid = new Mindfly_Validate_Calendar_Category();
        $this->assertFalse($valid->isValid($title));
        $this->assertEquals($errorMess, array_values($valid->getMessages()));
    }

    public function providerInvalidTitle() {
        return array(
            array('sdfffffffffffffffffffffffffffffffffffffffffffffffffffffffffff',
                   array('Название категории слишком длинное')
                 ),
            array('s',
                   array('Название категории слишком короткое')
                 ),
            array('<tag>ssd</tag>',
                   array('Название категории невалидно')
                 ),
        );
    }


    /**
     * проверяем правильность вывода шаблона об ошибке для уже существующей категории
     */
    public function testValidatorMessageExists() {
        $title = TestTools::getRandomString(time());
        $calendarCategory = new MfCalendarCategoryTable();
        $calendarCategory->create($title);
        $valid = new Mindfly_Validate_Calendar_Category();
        $this->assertFalse($valid->isValid($title));
        $this->assertEquals(array('Желаемая категория уже существует'),
                            array_values($valid->getMessages()));
    }
}
?>
