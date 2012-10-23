<?php
class Zend_View_Helper_CalendarList extends Zend_View_Helper_Abstract {
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

    public function calendarList ($data, $nets, $eventArray, $dataGroup) {
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

        // информация о групповых праздниках
        $output.='<table class="span-20 group_events">';
        if ($dataGroup) {
            foreach  ($dataGroup as $eventId => $days) {
                $output.='<tr>
                              <td class="day">' . current($days) . '</td>
                              <td class="">' . $eventArray[$eventId]->getText()->title . '</td>
                          </tr>';
            }
        }
        $output .='</table>';
        $output .= '</div>';


        return $output;
    }

    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }




}