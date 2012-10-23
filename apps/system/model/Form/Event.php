<?php
/**
 * форма авторизации
 */
class Form_Admin_Event extends Zend_Form
{
    private
        $rawfilename,
        $rediska,
        $key;
    public
        $status,
        $event;


    public function  __construct() {
        parent::__construct(new Zend_Config_Ini(SYSTEM_PATH . '/config/forms.ini','event'));
    }

    public function initDefaults($params) {
        $this->getElement('category')->setValue($params['category']);
        $this->getElement('category_id')->setValue($params['category_id']);
    }


    public function setEventId($id) {
        $event = Sancta_Peer_Event::getById($id);
        $this->event = $event;                
        $defaults = array(
             'id' => $event->getId(),
             'title' => $event->getTitle(),
             'annonce' => $event->getAnnonce(),
             'description' => $event->getContent(),
             'category' => $event->getParentIds(),
             'smart_function' => $event->getSmartFunction(),             
             'periodic' => (int) $event->isPeriodic(),
        );
        $this->setDefaults($defaults);        
        $this->getElement('drivePanel')->setAttrib('statusId', $event->getId());
        $this->getElement('drivePanel')->setAttrib('status', $event->getStatus());
    }


    public function getRawFileName() {
        return $this->rawfilename;
    }

    public function setRawFileName($ext) {
        $this->rawfilename = time() . '_' . rand(100, 999) . '.' . $ext;
        $this->image->addFilter('Rename', PATH_BASE . IMAGE_RAW_PATH . $this->rawfilename);
    }

    public function init() {
        
        $this->getElement('category')->setMultiOptions($this->initCategeories());
                
        $this->setDecorators(array(
             array('ViewScript', array('viewScript' => 'forms/event.phtml'))
        ));
    }


    public function initCategeories() {
        /**
         * получаем все категории
         */
        $defaultNamespace = new Zend_Session_Namespace('Default');
        $categoryes = new MfCalendarCategoryTable();
        if ($categoryId = $defaultNamespace->categoryId) {           
            $category = $categoryes->get($categoryId);
            $categoryes = $category->getChildrens();
        } else {
            throw new Exception('category is not find');
        }

        foreach ($categoryes as $category) {
            $result[$category->id] = $category->getObject()->getText()->title;
        }
        return $result;
    }


   

}
?>