<?php
class Zend_View_Helper_CategoryesTree extends Zend_View_Helper_Abstract {
    public $view;

    public function categoryesTree ($categories) {
        $output = '';
        foreach ($categories as $category) {
            
            $title = $category['object']->getText()->title;
            $output.= '<li>';                        
            $output.= '<a href="#"'
                    . ' id='.$category['object']->id.'>' . $title .'</a>';
            
            if (!empty($category['sub'])) {
                $output.= '<ul>';
                $output.= $this->categoryesTree($category['sub']);
                $output.= '</ul>';
            }
            $output.= '</li>';
        }

        return $output;
    }

    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }



   
}