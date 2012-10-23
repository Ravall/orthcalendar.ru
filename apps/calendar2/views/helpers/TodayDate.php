<?php
/**
 *  формы глагола проходить в зависимости от числа и рода
 */
class Zend_View_Helper_TodayDate extends Zend_View_Helper_Abstract {
    public function TodayDate() {
       $date = new Mindfly_Date();
       return $date->getDay();
    }
}