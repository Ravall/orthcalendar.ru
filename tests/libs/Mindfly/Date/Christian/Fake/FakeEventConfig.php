<?php
require_once dirname(__FILE__) . '/FakeFastConfig.php';
require_once dirname(__FILE__) . '/FakeOtherConfig.php';
/**
 * Фейковый класс конфигурации для постов и связанных с ними событий
 */
class FakeEventConfig {
    public $fast;
    public $fastweek = 65;    
    public function  __construct() {
        $this->fast = new FakeFastConfig();
        $this->other = new FakeOtherConfig();
    }
}
?>