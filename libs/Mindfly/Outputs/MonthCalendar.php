<?php
/*
 * Хелпер для вывода календаря на месяц
 *
 */
class Mindfly_Outputs_MonthCalendar extends Zend_View_Helper_Abstract {

    private $month = array(
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


    /**
     * выводит календарь на месяц
     * 
     * @param <type> $year
     * @param <type> $month
     * @return string
     */
    public function monthCalendar($year, $month, $dates = array()) {
        
        $timestamp = strtotime($year . '-' . $month . '-01');
        $weekOfFirstDay = date('N', $timestamp);
        for ($i = 1; $i < $weekOfFirstDay; $i++) $map[] = 0;
        $lastDayOfMonth = date('t', $timestamp);
        for ($i = 1; $i < $lastDayOfMonth; $i++) $map[] = $i;        
        $output = "<div class = 'month_year'>".$this->month[$month]."</div>";
        $output.= '<table class = "calendar_table">';
        $output.= '<tr><td>Пн</td><td>Вт</td><td>Ср</td><td>Чт</td><td>Пт</td><td>Сб</td><td>Вс</td></tr><tr>';
        $i = 1;        
        foreach ($map as $day) {
            $date = $year.'-'.$month.'-'.sprintf('%02s',$day);
            $class = in_array($date, $dates)?'selected':'';
            $output.= '<td id = "'.$date.'" class="'.$class.'">' . ($day ? ('<a href="#" id="'.$date.'">'. $day .' </a>') : '&nbsp') . '</td>';
            $i++;
            if ($i>7) {
                $i = 1;
                $output.= '</tr><tr>';
            }
        }
        $output.= '</tr></table>';
        return $output;
    }


    
}

?>

