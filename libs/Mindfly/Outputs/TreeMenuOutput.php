<?php
class Mindfly_Outputs_TreeMenuOutput extends Zend_View_Helper_Abstract {
    public $view;

    public function treeMenuOutput($categories, $mainCategoryId) {
        $output = '';
        foreach ($categories as $category) {
            $status = $category['object']->getObject()->status;
            $title = $category['object']->getText()->title;
            $output.= '<li> ';
            $class = ($status == STATUS_ACTIVE) ? 'category_active' : 'category_deleted';

            if ($status == STATUS_ACTIVE) {
                $output .= $this->eventButton($category,$mainCategoryId);
                $output .= ' ' . $this->createButton($category);
            }
            $output .= '<a'
                    . ' class = "' . $class . '" '
                    . ' href="/calendar/category-edit/id/' . $category['object']->id . '"'
                    . ' id='.$category['object']->id.'><span class="alt"> (#' . $category['object']->id . ')</span> ' .  $title .'</a>';

            

            $output .= $this->view->action(
                        'list',
                        'event',
                        null,
                        array(
                            'category_id' => $category['object']->id
                        )
                    );
            if (!empty($category['sub'])) {
                $output.= '<ul>';
                $output.=$this->treeMenuOutput($category['sub'], $mainCategoryId);
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
   
    /**
     * Кнопка создания категории
     * 
     * @param <type> $category
     * @return string
     */
    private function createButton($category) {
          $output= '<a title = "Создать категорию"
                    href="/calendar/category-create/id/'.$category['object']->id.'/">';
          $output.= $this->view->iconOutput('create');
          $output.= '</a>';
          return $output;
    }



    /**
     * кнопка добавления событий
     * 
     * @param <type> $category
     * @return string
     */
    private function eventButton($category, $mainCategoryId) {
          $output= '<a title = "cобытия"
                    href="/event/create/category_id/' . $mainCategoryId . '/category/'.$category['object']->id . '">';
          $output.= $this->view->iconOutput('calendar');
          $output.= '</a>';
          return $output;
    }
}