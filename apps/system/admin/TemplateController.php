<?php
require_once 'SystemController.php';
require_once SYSTEM_PATH . '/model/Form/Template.php';
require_once SYSTEM_PATH . '/model/Form/Filter.php';
require_once PATH_BASE . '/models/package/Sancta/Template.php';

/*
 * Системные настройки
 */
class TemplateController extends SystemController {
   private $listUri = '/template/list';
   private $filterUri = '/template/filter';
    // стили
   protected $_css = array(
        'list'   => array('thickbox.css', 'jquery.alerts.css','article.css'),
        'add'   => array('jquery.alerts.css'),
        'edit'   => array('jquery.alerts.css'),
    );
    // скрипты
    protected $_js = array(
        'list' => array('thickbox.js', 'jquery.alerts.js'),
        'add'   => array('jquery.alerts.js'),
        'edit'   => array('jquery.alerts.js'),
    );

    public function init() {
        parent::init();
        $this->defaultNamespace = new Zend_Session_Namespace('Default');
        $this->defaultNamespace->statusTemplatesFilter =  
            $this->defaultNamespace->statusTemplatesFilter ? $this->defaultNamespace->statusTemplatesFilter :
            array(STATUS_ACTIVE, STATUS_PAUSE);
    }

    /**
     * Фильтруем
     */
    public function filterAction() {
        $form = new Form_Admin_Filter($this->filterUri);
        if ($this->getRequest()->isPost()) {
           if ($form->isValid($_POST)) {              
               $this->defaultNamespace->statusTemplatesFilter = $form->getFilter();
               $this->_redirect($this->listUri);
           }
        }
    }

    /**
     * получаем список шаблонов,
     * выводим форму фильтра
     */
    public function listAction() {        
        $templateList = Sancta_Peer_Template::getAll();        
        $templateList = $templateList->statusFilter(
            new Sancta_Bundle_StatusesParam($this->defaultNamespace->statusTemplatesFilter)
        );
        $filterForm = new Form_Admin_Filter($this->filterUri);
        $filterForm->setFilter($this->defaultNamespace->statusTemplatesFilter);
        $this->view->templateList = $templateList;
        $this->view->form = $filterForm;
    }

    /**
     * Добавляем шаблон
     */
    public function addAction() {
        $form = new Form_Admin_Template();
        if ($this->getRequest()->isPost()) {
           if ($form->isValid($_POST)) {
               $article = Sancta_Peer_Template::create(array(
                   Sancta_Text::TITLE => $form->getValue('title'),
                   Sancta_Text::CONTENT => $form->getValue('description'),
                   Sancta_Text::ANNONCE => $form->getValue('annonce'),
               ));
               $this->_redirect($this->listUri);
           }
        }
        $this->view->form = $form;
    }

    public function deleteAction() {        
        $template = Sancta_Peer_Template::getById($this->getRequest()->getParam('id'));
        $template->setDeleted();
        $this->_redirect($this->listUri);
    }

    public function editAction() {
        $form = new Form_Admin_Template();
        $id = $this->getRequest()->getParam('id');
        $form->setTemplateId($id);
        if ($this->getRequest()->isPost()) {
           if ($form->isValid($_POST)) {
               $template = Sancta_Peer_Template::getById($id);
               $template->setContent($form->getValue('description'));
               $template->setAnnonce($form->getValue('annonce'));
               $template->setTitle($form->getValue('title'));
               $this->_redirect($this->listUri);
           }
        }
        $this->view->form = $form;
    }
}