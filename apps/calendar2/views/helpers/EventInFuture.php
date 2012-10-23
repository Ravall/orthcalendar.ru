<?php
/**
 *  выводим даты событий в будущем
 */
class Zend_View_Helper_EventInFuture extends Zend_View_Helper_Abstract {
    
    private function prepare($event, $date) {
        for (${0}=-3; ${0}<10; ${0}++) {
            $datesOfEvent = SmartFunction::getDates($event->getSmartFunction(), $date->getY()+${0});
            $noteForFuture['в ' . ( $date->getY()+${0}) . ' году: '] = SmartFunction::toString($datesOfEvent);
        }        
        $values = array_values($noteForFuture);
        sort($values);
        if ($values[0] == $values[count($values)-1]) {
            return false;
        }        
        return $noteForFuture;        
    }
    
    public function EventInFuture($event, $date) {
        $datesOfEvent = SmartFunction::getDates(
            $event->getSmartFunction(),
            $date->getY()+1
        );
        if ($event->getId() == Config_Interface::get('everyWeekFast', 'events')) {
            return $this->EventInFuture_EveryWeekFast($datesOfEvent, $date);
        }
        $output = 'в ' . ( $date->getY()+1) . ' году : ' . SmartFunction::toString($datesOfEvent);
        $output .= ', <br/><a href="javascript:void(0);" id="to_future_show">в иные года</a><div id="otherDates">';
        if ($noteForFuture = $this->prepare($event, $date)) {
            foreach ($noteForFuture as $year => $days) {
                $output .= $year . $days. '<br/>';
            }            
        } else {
            $output .= 'Событие непереходящее - ежегодно бывает в одни и те же дни года.';
        }
         $output .= '</div>';
        return $output;
    }
    
    public function EventInFuture_EveryWeekFast($datesOfEvent, $date) {
        return 'в ' . ( $date->getY()+1) . ' году : ' . SmartFunction::toString($datesOfEvent);
    }
}