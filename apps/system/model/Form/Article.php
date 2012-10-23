<?php
/**
 * форма добавления статьи
 */
class Form_Admin_Article extends Zend_Form
{
    private
        $key;

    public function  __construct() {
        parent::__construct(new Zend_Config_Ini(SYSTEM_PATH . '/config/forms.ini','article'));
    }

    public function setArticleId($id) {
        $acticle = Sancta_Peer_Article::getById($id);
        $this->setDefaults(array(
            'id' => $acticle->getId(),
            'title' => $acticle->getTitle(),
            'description' => $acticle->getContent(),
            'relation' => implode(', ',$acticle->getRelatedEvents()),
        ));        
        $this->getElement('drivePanel')->setAttrib('statusId', $acticle->getId());        
    }



    public function  init() {
        parent::init();
        $this->setDecorators(array(
            array('ViewScript', array('viewScript' => 'forms/article.phtml'))
        ));
    }

}