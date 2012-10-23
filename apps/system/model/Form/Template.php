<?php
/**
 * форма добавления статьи
 */
class Form_Admin_Template extends Zend_Form
{

    public function  __construct() {
        parent::__construct(new Zend_Config_Ini(SYSTEM_PATH . '/config/forms.ini','template'));
    }


    public function setTemplateId($id) {
        $template = Sancta_Peer_Template::getById($id);
        $this->setDefaults(array(
            'id' => $template->getId(),
            'title' => $template->getTitle(),
            'annonce' => $template->getAnnonce(),
            'description' => $template->getContent(),
        ));
        $this->getElement('drivePanel')->setAttrib('statusId', $template->getId());
    }

    public function  init() {
        parent::init();
        $this->setDecorators(array(
            array('ViewScript', array('viewScript' => 'forms/template.phtml'))
        ));
    }
}