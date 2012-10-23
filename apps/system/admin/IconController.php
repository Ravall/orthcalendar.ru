<?php
require_once 'SystemController.php';
require_once SANCTA_PATH . '/Peer/Icon.php';
require_once SYSTEM_PATH . '/model/Form/Icon.php';
/*
 * Контроллер управления иконами
 */
class IconController extends SystemController
{
	public function init()
	{
        parent::init();
        $this->defaultNamespace = new Zend_Session_Namespace('Default');
    }


    public function listAction()
    {
    	$iconList = Sancta_Peer_Icon::getAll();
    	$this->view->iconList = $iconList;
    }

    public function editAction()
    {
        $form = new Form_Admin_Icon();
        $id = $this->getRequest()->getParam('id');
        $form->setIconId($id);

        if ($this->getRequest()->isPost()) {
           if ($form->isValid($_POST)) {
             $icon = Sancta_Peer_Icon::getById($id);
             $icon->setTitle($form->getValue('title'));
             $icon->relateToEvent($form->getValue('event_id'));
             if ($form->getValue('deleteImage')) {
                $icon->deleteImage();
             }
           } else {
            $errorsMessages = $form->getMessages();
            var_dump($errorsMessages);
           }
        }
        $this->view->form = $form;
    }

    public function addAction()
    {
    	 $form = new Form_Admin_Icon();
    	 if ($this->getRequest()->isPost()) {
             if ($form->isValid($_POST)) {
             	$icon = Sancta_Peer_Icon::create(array(
                   Sancta_Text::TITLE => $form->getValue('title'),
                ));
                $icon->relateToEvent($form->getValue('event_id'));

                $file = $form->image->getFileInfo();

                $ext = split("[/\\.]", $file['image']['name']);
                $form->setRawFileName($ext[count($ext)-1]);

                if ($form->image->receive() && $form->image->isUploaded()) {
                    $icon->setImage($form->getRawFileName());
                }
                $this->_redirect('/icon/list');
             }
         }
    	 $this->view->form = $form;
    }


}