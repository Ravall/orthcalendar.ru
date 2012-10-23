<?php
require_once 'SystemController.php';
require_once SYSTEM_PATH . '/model/Form/Article.php';
require_once SYSTEM_PATH . '/model/Form/Filter.php';
require_once SANCTA_PATH . '/Peer/Article.php';

/*
 * Контроллер управления статьями
 */
class ArticleController extends SystemController {
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
        $this->defaultNamespace->statusFilter =  $this->defaultNamespace->statusFilter ? $this->defaultNamespace->statusFilter : array(
            STATUS_ACTIVE, STATUS_PAUSE
        );
    }


    public function filterAction() {
        $form = new Form_Admin_Filter();   
        if ($this->getRequest()->isPost('/article/filter')) {
           if ($form->isValid($_POST)) {                      
               $this->defaultNamespace->statusFilter = $form->getFilter();
               $this->_redirect('/article/list');
           } 
        }
    }

    public function listAction() {        
        $artcileList = Sancta_Peer_Article::getAll();
        $artcileList = $artcileList->statusFilter(
            new Sancta_Bundle_StatusesParam($this->defaultNamespace->statusFilter)
        );
        $filterForm = new Form_Admin_Filter('/article/filter');
        $filterForm->setFilter($this->defaultNamespace->statusFilter);
        $this->view->articleList = $artcileList;
        $this->view->form = $filterForm;
    }

    public function addAction() {
        $form = new Form_Admin_Article();
        if ($this->getRequest()->isPost()) {
           if ($form->isValid($_POST)) {
               $article = Sancta_Peer_Article::create(array(
                   Sancta_Text::TITLE => $form->getValue('title'),
                   Sancta_Text::CONTENT => $form->getValue('description'),
               ));
               foreach (explode(',', $form->getValue('relation')) as $eventId) {
                   $eventId = trim($eventId);
                   $article->relateToEvent($eventId);
               }               
               $this->_redirect('/article/list');       
           } 
        }
        $this->view->form = $form;
    }

    public function deleteAction() {        
        $acticle = Sancta_Peer_Article::getById($this->getRequest()->getParam('id'));
        $acticle->setDeleted();
        $this->_redirect('/article/list');       
    }

    public function editAction() {
        $form = new Form_Admin_Article();
        $id = $this->getRequest()->getParam('id');
        $form->setArticleId($id);
        if ($this->getRequest()->isPost()) {
           if ($form->isValid($_POST)) {
               $article = Sancta_Peer_Article::getById($id);
               $article->setContent($form->getValue('description'));
               $article->setTitle($form->getValue('title'));
               $relations = $form->getValue('relation');
               $article->setRelates(empty($relations) ? array() : explode(',', $form->getValue('relation')));
               
               $this->_redirect('/article/list');
           }
        }
        $this->view->form = $form;
    }

    /**
     * ajax action сохрананеия черновика
     */
    public function savedraftAction() {
        $this->setLayout('ajax');
        $id = (int) $this->getRequest()->getParam('id');
        $content = $this->getRequest()->getParam('content');
        $this->view->draft = Sancta_Text::setDraft($key = 'acticle_' . $id, $content);
    }
}