<?php
require_once realpath(dirname(__FILE__).'/../../../../') . '/config/init.php';
require_once PATH_TESTS . '/init.php';
require_once PATH_LIBS . '/Mindfly/Grammar.php';
/**
 * Тестирование модуля грамматики
 *
 * @author ravall
 */
class Mindfly_GrammarTest extends PHPUnit_Framework_TestCase {
    public function providerNounDeclension() {
        $events = array('событие','события','событий');
        return array(
            array($events, '1', 'событие'),
            array($events, '2', 'события'),
            array($events, '3', 'события'),
            array($events, '5', 'событий'),
            array($events, '9', 'событий'),
            array($events, '21', 'событие'),
            array($events, '23', 'события'),
            array($events, '99', 'событий'),
        );
    }
    
    /**
     * тест склонения
     * @dataProvider providerNounDeclension
     * @param <type> $forms
     * @param <type> $value
     * @param <type> $word
     */
    public function testNounDeclension($forms, $value, $word) {
        $this->assertEquals($word, Mindfly_Grammar::nounDeclension($value, $forms));
    }
}