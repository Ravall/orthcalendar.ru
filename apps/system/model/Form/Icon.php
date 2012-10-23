<?php
/**
 * форма добавления статьи
 */
class Form_Admin_Icon extends Zend_Form
{
    private $rawfilename;
    public $icon;

    public function  __construct() 
    {
        parent::__construct(
            new Zend_Config_Ini(SYSTEM_PATH . '/config/forms.ini', 'icon')
        );
    }

    public function setIconId($id) {
        $icon = Sancta_Peer_Icon::getById($id);
        $this->icon = $icon; 
        $this->setDefaults(array(
            'id' => $icon->getId(),
            'title' => $icon->getTitle(),
            'event_id' => $icon->getRelatedEvent(),
        ));        
        $this->getElement('drivePanel')->setAttrib('statusId', $icon->getId());        
    }



    public function  init() {
        parent::init();
        $this->setDecorators(array(
            array('ViewScript', array('viewScript' => 'forms/icon.phtml'))
        ));
    }

    public function getRawFileName() 
    {
        return $this->rawfilename;
    }

    public function setRawFileName($ext) 
    {
        $this->rawfilename = time() . '_' . rand(100, 999) . '.' . $ext;
        $this->image->addFilter('Rename', PATH_BASE . IMAGE_RAW_PATH . $this->rawfilename);
    }

}