<?php
/**
 * Класс вывода календарика
 */
class Calendar_Days {
    const CACHE_INDEX_PREFIX = 'calendar_days_';
    private 
        $currentDay, // выбранный день
        $id; // идентификатор DOM таблицы календаря

    private $monthNames = array(
        '01' => 'Январь',
        '02' => 'Февраль',
        '03' => 'Март',
        '04' => 'Апрель',
        '05' => 'Май',
        '06' => 'Июнь',
        '07' => 'Июль',
        '08' => 'Август',
        '09' => 'Сентябрь',
        '10' => 'Октябрь',
        '11' => 'Ноябрь',
        '12' => 'Декабрь',
    );
    public function  __construct($config) {
         $this->config = new Zend_Config_Ini(PATH_BASE . '/config/calendar.ini', $config);
    }

    private function _getPrevLink($month, $year) {
        $prevMonth = dateShift(1, $month, $year, -1);
        array_walk($prevMonth, create_function('&$val', '$val = sprintf("%02s", $val);'));
        list($prevMonthDay, $prevMonthMonth, $prevMonthYear) = $prevMonth;
        $linkToPrevMonth = $prevMonthYear . '-' . $prevMonthMonth . '-01';
        return $this->config->action . '/'. $linkToPrevMonth;
    }

    private function _getNextLink($month, $year) {
        $nextMonth = dateShift(numDaysInMonth($month, $year), $month, $year, 1);
        array_walk($nextMonth, create_function('&$val', '$val = sprintf("%02s", $val);'));
        list($nextMonthDay, $nextMonthMonth, $nextMonthYear) = $nextMonth;
        $linkToNextMonth = $nextMonthYear . '-' . $nextMonthMonth . '-01';
        return $this->config->action . '/'. $linkToNextMonth;
    }


    private function gereatEvents($month, $year) {
        $mapper = new Db_Mapper_CalendarNet();
        if (!$this->config->eventIds) return array();
        $ids = explode(',',  $this->config->eventIds);
        
        $result =  $mapper->getDaysInMonthWhereIdIn($year, $month, $ids);
        return $result;
    }



    private function _header($month, $year) {
        $output= '<table id="calendar_table">';
        $output.= '<tr>
                       <td>&nbsp;</td>
                       <td><a class="' . $this->config->larrarrClass . '" href="/' . $this->_getPrevLink($month, $year).'" rel="nofollow">&larr;</a></td>
                       <td colspan="3" class="' . $this->config->monthnameClass .'">' . $this->monthNames[$month] . '</td>
                       <td><a class="' . $this->config->larrarrClass . '" href="/' . $this->_getNextLink($month, $year).'" rel="nofollow">&rarr;</a></td>
                       <td>&nbsp;</td>
                   </tr>';
        $output.= '<tr class="weeks">
                       <td>Пн</td>
                       <td>Вт</td>
                       <td>Ср</td>
                       <td>Чт</td>
                       <td>Пт</td>
                       <td>Сб</td>
                       <td><span class="weekend">Вс</span></td>
                    </tr>
                    <tr>';
        return $output;
    }


    public function getHtmlForMonth($month, $year, $currentDay) {
        $this->gereatEvents = $this->gereatEvents($month, $year);
        $currentDay = sprintf('%02s', $currentDay);
        $map = array();
        for (${0} = 1; ${0} < getDayOfWeek(1, $month, $year); ${0}++) {
            $map[] = 0;
        }        
        for (${0} = 1; ${0} <= numDaysInMonth($month, $year); ${0}++) {
            $map[] = ${0};
        }
        $i = 1;
        $output = $this->_header($month, $year);
        foreach ($map as $day) {
            $date = $year . '-' . $month . '-' . sprintf('%02s', $day);
            
            $output.= '<td ' . $this->getTdClass($day, $date, $currentDay,$month, $year) . '>';
            if ($day) {
                $output.= '<a href="/' . $this->config->action. '/' . $date . '">'. $day .' </a>';
            }                   
            $output.='</td>';
            $i++;
            if ($i>7) {
                $i = 1;
                $output.= '</tr><tr>';
            }
        }
        $output.= '</tr></table>';
        return $output;
    }
    
    /**
     * css класс ячейки таблицы
     * 
     * @param type $day
     * @param type $date
     * @param type $currentDay
     * @param type $month
     * @param type $year
     * @return type 
     */
    public function getTdClass($day, $date, $currentDay,$month, $year) {
        $classes = array();
        if ($date == date('Y-m-d',time())) {
            $classes[] = 'today';
        }
        if ($date == $year . '-' . $month . '-' . $currentDay) {
            $classes[] = 'current';
        }
        if (in_array($day, $this->gereatEvents)) {
            $classes[] = 'great';
            $classes[] = 'holiday';
        }
        if (in_array(getDayOfWeek($day, $month, $year), array(7))) {
            $classes[] = 'weekend';
        }        
        return empty($classes) ? '' : ('class="' . implode(' ', $classes) . '"');
        
    }

    public static function html($month, $year, $currentDay, $config) {
        $calendar = new self($config);
        $html = $calendar->getHtmlForMonth($month, $year, $currentDay);
        return $html;        
    }
}
