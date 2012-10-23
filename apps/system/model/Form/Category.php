<?php
/**
 * форма Категории
 */
class Form_Admin_Category extends Zend_Form
{
    public  $status,
            $category;

    public function setCategoryId($id) {        
        $categoryClass = new MfCalendarCategoryTable();
        $category = $categoryClass->get($id);
        $defaults = array(
            'id' => $category->id,
            'parent_id' => $category->getObject()->parent_id,
            'title' => $category->getText()->title,
            'description' => $category->getText()->content,
        );
        $this->setDefaults($defaults);
        $this->status = $category->getObject()->status;
        $this->category = $category;
    }


    public function init() {
        $this->status = STATUS_ACTIVE;
        $this->setMethod('post');

        $title = new Zend_Form_Element_Text('title',array('class' => 'title'));
        $title->setLabel('Заголовок');
        $title->setRequired();
        $title->addValidator(new Mindfly_Validate_Calendar_Category());
        $this->addElement($title);

        $parentId = new Zend_Form_Element_Hidden('parent_id');
        $this->addElement($parentId);


        $id = new Zend_Form_Element_Hidden('id');
        $this->addElement($id);

        $description = new Zend_Form_Element_Textarea('description');
        $description->setLabel('Описание');
        $this->addElement($description);

        $this->setDecorators(array(
            array('ViewScript', array('viewScript' => 'forms/category.phtml'))
        ));

    }
}