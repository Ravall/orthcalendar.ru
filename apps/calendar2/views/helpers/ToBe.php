<?php
/**
 *  формы глагола проходить в зависимости от числа и рода
 */
class Zend_View_Helper_ToBe extends Zend_View_Helper_Abstract {
    public function ToBe($event, $date, $datesOfEvent) {
       if ($event->getId() == Config_Interface::get('adventId','events')) {
           return self::ToBe_Advent($event, $date, $datesOfEvent);
       }
       if ($event->getReestrParam('number') && $event->getReestrParam('number') === 'plural') {
           // множественное число
           if ($datesOfEvent[0] > $date->getDay()) {
                $output = 'будут проходить';
           } elseif($datesOfEvent[count($datesOfEvent)-1] < $date->getDay()) {
               $output = 'проходили';
           } else {
               $output = 'проходят';
           }
       } else {
           // единственное число
           if ($datesOfEvent[0] > $date->getDay()) {
               $output = 'будeт проходить';
           } elseif($datesOfEvent[count($datesOfEvent)-1] < $date->getDay()) {
               $gender = $event->getReestrParam('gender');
               if ($gender === 'feminine') {
                   $output = 'проходила';
               } elseif($gender === 'neuter') {
                   $output = 'проходило';
               } else {
                   $output = 'проходил';
               }
           } else {
               $output = 'проходит';
           }
       }
       return $output;
    }


    /**
     * особая логика на рождественский пост
     * @param <type> $event
     * @param <type> $date
     * @param <type> $datesOfEvent
     * @return <type>
     */
    public function ToBe_Advent($event, $date, $datesOfEvent) {
        if (($date->getM() == 1 && $date->getD() <7)
           || ($date->getM() .'-' . $date->getD()  >= '11-28')) {
            return 'проходит';
        }
        return $date->getM()<6 ? 'проходил' : 'будет проходить';
    }
}