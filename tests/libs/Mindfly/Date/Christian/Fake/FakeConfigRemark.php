<?php
require_once dirname(__FILE__).'/FakeConfig.php';
require_once dirname(__FILE__).'/FakeEventConfig.php';
require_once dirname(__FILE__).'/FakeMeatfareConfig.php';
require_once dirname(__FILE__).'/FakeSemidnizaConfig.php';
require_once dirname(__FILE__).'/FakeGreatFeastsConfig.php';
require_once dirname(__FILE__).'/FakeFortyDayFast.php';

class FakeConfigRemark {
    public $remark;
    public $event;
    public function __construct() {
        $this->remark = new FakeConfig();
        $this->event = new FakeEventConfig();
        $this->meatfare = new FakeMeatfareConfig();
        $this->semidniza = new FakeSemidnizaConfig();
        $this->passion = new FakePassionConfig();
        $this->greatFeasts = new FakeGreatFeastsConfig();
        $this->fortyDayFast = new FakeFortyDayFast();
    }
}
?>
