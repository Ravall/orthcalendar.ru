<?php
require_once 'SystemController.php';
require_once SANCTA_PATH . '/Event.php';
require_once SANCTA_PATH . '/Peer/Event.php';
require_once SANCTA_PATH . '/Article.php';
require_once SYSTEM_PATH . '/model/Form/Event.php';
require_once PATH_BASE . '/models/package/SmartFunction/SmartFunction.php';

/*
 * Админка календаря
 */
class EventController extends SystemController {

   // стили
   protected $_css = array(
        'edit' => array('jquery.alerts.css','redactor/redactor.css'),
    );
    // скрипты
    protected $_js = array(
        'edit' => array('event.js','redactor/redactor.js','jquery.alerts.js'),
    );

    public function listAction() {
        $categoryId = (int) $this->getRequest()->getParam('category_id');        
        $events = Sancta_Peer_Event::getByCategoryId($categoryId);        
        $this->view->events = $events;
    }

    public function smartfunctionAction() {
        $strFormula = $this->getRequest()->getParam('formula');        
        try {
            for ($year=2010; $year<=2020; $year++) {
               $dates[] = $year . ' : ' . SmartFunction::toString(SmartFunction::getDates($strFormula, $year));
            }            
            $this->view->result = implode('<br/>', $dates);
        } catch (Exception $e) {
            $this->view->result = $e->getMessage();
        }
        $this->setLayout('ajax');
    }

    public function savedraftAction() {
        $this->setLayout('ajax');
        $id = (int) $this->getRequest()->getParam('id');
        $content = $this->getRequest()->getParam('content');         
        $this->view->draft = Sancta_Text::setDraft($key = 'event_' . $id, $content);        
    }


    public function editAction() {
        
        $form = new Form_Admin_Event();
        
        $form->setEventId($this->getRequest()->getParam('id'));        
        
        if ($this->getRequest()->isPost() && $form->isValid($_POST)) {
            $event = Sancta_Peer_Event::getById($form->getValue('id'));
            $event->update($x = array(
                  'title' => $form->getValue('title'),
                  'annonce' => $form->getValue('annonce'),
                  'content' => $form->getValue('description'),
                  'smart_function' => $form->getValue('smart_function'),
                  'periodic' => $form->getValue('periodic'),
            ));
               
            $event->relateToCategory($form->getValue('category'));            
            $file = $form->image->getFileInfo();            
            $ext = split("[/\\.]", $file['image']['name']);
            $form->setRawFileName($ext[count($ext)-1]);
            if ($form->getValue('deleteImage')) {
                $event->deleteImage();
            }
            if ($form->image->receive() && $form->image->isUploaded()) {
                $event->setImage($form->getRawFileName());
            }
            if ($this->getRequest()->getParam('active')) {
              $event->setActived();
            }
            $this->flash->addMessage('Событие успешно отредактировано');
            $this->_redirect('/event/edit/id/' . $form->getValue('id'));            
        }
        $this->view->form = $form;
    }

    public function createAction() {
        if (!$categoryId = (int) $this->getRequest()->getParam('category_id')) {
            throw new Exception('Главная категория не задана');
        }
        $form = new Form_Admin_Event();
        $form->initDefaults(array(
            'category' => $this->getRequest()->getParam('category'),
            'category_id' => $categoryId                
        ));        
        if ($this->getRequest()->isPost() && $form->isValid($_POST)) {
            $event = Sancta_Peer_Event::create(array(
                'title' => $form->getValue('title'),
                'annonce' => $form->getValue('annonce'),
                'content' => $form->getValue('description'),
                'smart_function' => $form->getValue('smart_function'),
                'parent_id' => $categoryId,
                'periodic' => $form->getValue('periodic'),
            ));
            $event->relateToCategory($form->getValue('category'));
            $this->flash->addMessage('Событие успешно создано');
            $this->_redirect('calendar/category-list/id/' . $categoryId);
        }
        $this->view->form = $form;        
    }

    public function deleteAction() {
        $event = Sancta_Event::getById($this->getRequest()->getParam('id'));
        $event->setDeleted();
        $this->_redirect('/event/edit/id/' . $event->getId());
    }
}