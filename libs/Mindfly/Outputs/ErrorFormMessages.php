
<?php
/*
 * Хелпер для вывода иконок
 *
 */
class Mindfly_Outputs_ErrorFormMessages extends Zend_View_Helper_Abstract {
    public function errorFormMessages($form) {
        $errors = $form->getMessages();
        $output = '';
        if (!empty($errors)) { 
            $output.= '<ul class="errors">';
            foreach ($errors as $errorElement) {
                if (is_array($errorElement)) {
                    foreach ($errorElement as $error) {
                        $output.='<li>'.$error.'</li>';
                    }
                } else {
                    $output.='<li>'.$errorElement.'</li>';
                }
            }
            $output.='</ul>';
        }
        return $output;
            
   }
}
