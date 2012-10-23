<?php
/**
 * Пост
 */
class Zend_View_Helper_Fasting extends Zend_View_Helper_Abstract {
    public $view;
    private $month;
    private $monthShort;

    public function  __construct() {
        $this->month = Mindfly_Date::getFullMonthNamesArray();
        $this->monthShort = Mindfly_Date::getShortMonthNamesArray();
        $this->monthPossessive = Mindfly_Date::getPossessiveMonthNamesArray();
    }


    public function dataTitle($data) {
        if (preg_match('/(\d{4})-(\d{2})/', $data, $array)) {
            $output = '<span>'.$this->month[$array[2]].'</span> <span>'.$array[1].'</span>';
        }

        return $output;
    }

    public function Fasting ($data, $nets, $eventArray) {
        $output = '';
        $output .= '<div class="title">
                      <span class="data">' . $this->dataTitle($data). '</span>
                      <br/>
                      <span class="category"><a href ="#">Православие</a></span>
                    </div><div class="list span-24 last"> ';

        if ($nets) {
            $output.='<table>';
            foreach  ($nets as $net) {



                $output.='<tr>
                            <td class="week">пн</td>
                            <td class="day">' .$net->day.'</td>
                            <td class="">' . $eventArray[$net->event_id]->getText()->title . '</td>
                          </tr>';

            }
            $output .='</table>';
        }
        $output .= '</div>';
        return $output;
    }

    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }


    private function day($dayEvents) {
        $output = '';
        foreach ($dayEvents as $event) {
            $output.= $event->getText()->title;
        }
        return $output;
    }


    private function month($monthEvents,$classDayNumber,$classWeek,$classEvent) {
        $output = '';return $output;
        foreach ($monthEvents as $day => $dayEvents) {
            $date = new Mindfly_Date($day);
            $output.= '<div class="' . $classDayNumber . '">' . $date->getD() . '</div>'
                    . '<div class="' . $classWeek . '">' . $date->getW() . '</div>'
                    . '<div class="' . $classEvent . '">'.$this->day($dayEvents).'</div>';
        }
        return $output;
    }

    private function normalise($events) {
        $array = array();
      //  ksort($events);
        foreach ($events as $day => $events) {
            $date = new Mindfly_Date($day);
            foreach ($events as $event) {
                $array[$date->getM()][$date->getDay()][] = $event;
            }
        }
        return $array;
    }


}