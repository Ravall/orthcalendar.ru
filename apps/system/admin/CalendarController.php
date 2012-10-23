<?php
require_once 'SystemController.php';
require_once PATH_LIBS . '/Mindfly/Validate/Calendar/Category.php';
require_once SYSTEM_PATH . '/model/Form/Category.php';
require_once SANCTA_PATH . '/Peer/Article.php';

/* 
 * Админка календаря
 */
class CalendarController extends SystemController {
   // стили
   protected $_css = array(
        'category-list'   => array('thickbox.css', 'jquery.alerts.css', 'category.css'),
        'category-edit'   => array('jquery.alerts.css'),
        'event-create'    => array('redactor/redactor.css'),              
        'event-days' => array('event-days.css'),
        'event-edit' => array('jquery.alerts.css','redactor/redactor.css'),
    );
    // скрипты
    protected $_js = array(
        'category-list' => array('jsTree/jquery.jstree.js', 'thickbox.js', 'jquery.alerts.js', 'category.js'),
        'category-edit'   => array('jquery.alerts.js'),
        'event-create'  =>  array('event.js','redactor/redactor.js'),
        'event-days' => array('event-days.js'),
        'event-edit' => array('event.js','redactor/redactor.js','jquery.alerts.js'),
    );

    public function  init() {
        parent::init();        
        $this->addTitle('Календарь');        
        $this->defaultNamespace = new Zend_Session_Namespace('Default');
    }

    /**
     * Список категорий
     * выводит дерево Категорий
     */
    public function categoryListAction() {                
        $this->addTitle('Категории');        
        $categoryes = new MfCalendarCategoryTable();
        // если указана категория_ид
        if ($categoryId = (int) $this->getRequest()->getParam('id')) {            
            $category = $categoryes->get($categoryId);
           
        }

        $this->defaultNamespace->categoryId = $categoryId;
        // запоминаем категорию
        
        
        $categoryes = MfSystemObjectTable::getTreeArray(
            ($categoryId) ? $category->getChildrens() : $categoryes->fetchAll()
        );
        $this->view->categoryes = $categoryes;
        $this->view->categoryId = $categoryId;
    }


    /**
     * Создание категории
     */
    public function categoryCreateAction() {
        $this->addTitle('Создать категорию');
        $form = new Form_Admin_Category();
        $form->setDefault('parent_id', $this->getRequest()->getParam('id'));
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($_POST)) {
                $categoryClass = new MfCalendarCategoryTable();
                $category = $categoryClass->create($form->getValue('title'));
                $category->setText(array('content' => $form->getValue('description')));
                if ($parentId = $form->getValue('parent_id')) {
                    // добавляем родительскую категорию
                    $parentCategory = $categoryClass->get($parentId);
                    $parentCategory->getObject()->addSubObject($category->getObject());
                }
                // редирект на список категорий
                $this->_redirect($this->__redirectCategorylist());
            }
        }
        $this->view->form = $form;
    }

    /**
     * Удаление категории
     */
    public function categoryDeleteAction() {
        $categoryClass = new MfCalendarCategoryTable();
        $category = $categoryClass->get($this->getRequest()->getParam('id'));
        $category->setDelete();        
        $this->_redirect($this->__redirectCategorylist());
    }

 

    /**
     * Редактирование категории
     */
    public function categoryEditAction() {
        $this->addTitle('Редактировать категорию');
        $form = new Form_Admin_Category();
        $form->setCategoryId($this->getRequest()->getParam('id'));
        if ($this->getRequest()->isPost()) {
            $categoryClass = new MfCalendarCategoryTable();
            $category = $categoryClass->get($form->getValue('id'));
            if ($form->isValid($_POST)) {                
                
                $category->setText(array(
                      'title' => $form->getValue('title'),
                      'content' => $form->getValue('description'),
                ));
                
                /**
                 * если сохраняем удаленный объект - то восстанавливаем
                 * это не относится к объектам поставленных на паузу
                 */
                if ($category->getObject()->status == STATUS_DELETED) {
                    $category->getObject()->setActive();
                }

                

                $this->_redirect('/calendar/category-edit/id/' . $form->getValue('id'));
            }            
        }
        $this->view->form = $form;
    }
    
   
    private function __redirectCategorylist()
    {
        $urlRedirect = 'calendar/category-list/'
                     . ($this->defaultNamespace->categoryId
                     ? ('id/' . $this->defaultNamespace->categoryId)
                     : '');
         return $urlRedirect;
    }


}

?>