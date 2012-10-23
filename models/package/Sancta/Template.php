<?php
require_once dirname(__FILE__) . '/Interface/Template.php';
require_once PATH_BASE . '/models/Db/Mapper/SystemTemplate.php';
require_once SANCTA_PATH . '/Abstract/System.php';
require_once SANCTA_PATH . '/Peer/Template.php';

class Sancta_Template extends Sancta_Abstract_System {
    private $templateId;
    protected $mapperTable = 'Db_Mapper_SystemTemplate';

    protected function _setModel($template) {
        $this->templateId = $template->id;     
    }
}
?>