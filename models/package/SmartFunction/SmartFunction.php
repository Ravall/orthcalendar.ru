<?php
require_once PATH_BASE . '/libs/SmartFunction/dateFunc.php';
class SmartFunction {

    private static $monthName = array(
        'ru' => array(
            'full' => array(
                '1' => 'Январь',
                '2' => 'Февраль',
                '3' => 'Март',
                '4' => 'Апрель',
                '5' => 'Май',
                '6' => 'Июнь',
                '7' => 'Июль',
                '8' => 'Август',
                '9' => 'Сентябрь',
                '10' => 'Октябрь',
                '11' => 'Ноябрь',
                '12' => 'Декабрь'
            ),
            'possessive' => array(
                '1' => 'января',
                '2' => 'февраля',
                '3' => 'марта',
                '4' => 'апреля',
                '5' => 'мая',
                '6' => 'июня',
                '7' => 'июля',
                '8' => 'августа',
                '9' => 'сентября',
                '10' => 'октября',
                '11' => 'ноября',
                '12' => 'декабря'
            ),
            'prepositional' => array(
                '01' => 'январе',
                '02' => 'феврале',
                '03' => 'марте',
                '04' => 'апреле',
                '05' => 'мае',
                '06' => 'июне',
                '07' => 'июле',
                '08' => 'августе',
                '09' => 'сентябре',
                '10' => 'октябре',
                '11' => 'ноябре',
                '12' => 'декабре'
            ),
            'short' => array(
                 '1' => 'янв',
                 '2' => 'фев',
                 '3' => 'мрт',
                 '4' => 'апр',
                 '5' => 'май',
                 '6' => 'июн',
                 '7' => 'июл',
                 '8' => 'авг',
                 '9' => 'сен',
                 '10' => 'окт',
                 '11' => 'нбр',
                 '12' => 'дек',
            )
        ),
    );

    public static function getPossessiveMonthNamesArray($month, $lang = LANG_DEFAULT)   {
        $array = self::$monthName;
        return $array[$lang]['possessive'][(int) $month];
    }
    public static function getFullMonthNamesArray($month, $lang = LANG_DEFAULT)   {
        $array = self::$monthName;
        return $array[$lang]['full'][(int) $month];
    }

    public static function getDates($strFormula, $year, &$result = null) {
        if (!$strFormula) {
            $result = array();
            return $result;
        }
        $result = smart_date_function($strFormula, $year);
        $resultNormal = array();
        foreach ($result as &$date) {         
            array_walk($date, create_function('&$val', '$val = sprintf("%02s", $val);'));
            $resultNormal[] = implode('-', array_reverse($date));
        }
        return $resultNormal;
    }

    public static function dateShift($date, $shift) {
        list($year, $month, $day) = explode('-', $date);        
        return implode('-', array_reverse(dateShift((int) $day, (int) $month, (int) $year, $shift)));
    }


    private static function _toString($date, &$lastMonth, &$output, &$first, &$count, $lastStep) {
        list($year, $month, $day) = explode('-', $date);
        $output.= $lastMonth != $month ?  ' ' . self::getPossessiveMonthNamesArray($lastMonth) : '';
        if (!$first) {
            $first = true;
            $output = $count > 2 ? 'с ' . $output : $output;
        }
        $output.= ($count > 2 ? ' по ' : ', ')
                . (int) $day
                . ($lastStep ? ' ' . self::getPossessiveMonthNamesArray($month) : '');
        $lastMonth = $month;
        $count = 1;
    }

    /**
     * превращение даты в с троку
     * 
     * @param <type> $array
     * @return string
     */
    public static function toString($array) {
        if (empty ($array)) {
            return 'нет данных';
        }
        $count = 1;
        list($year, $lastMonth, $day) = explode('-', $array[0]);
        $output = (int) $day;
        $first = false;
        if (count($array) != 1) {
            for ($i=1; $i<count($array)-1; $i++) {
                if (self::dateShift($array[$i], -1) == self::dateShift($array[$i-1],0) &&
                    self::dateShift($array[$i], 1) == self::dateShift($array[$i+1],0)) {
                    // дата текущая есть следущая после предыдущей
                    // и предыдущаяя для селедущей
                    $count++;
                    continue;
                }
                self::_toString($array[$i], $lastMonth, $output, $first, $count, false);
            }            
            self::_toString($array[$i], $lastMonth, $output, $first, $count, true);
        } else {
            $output.=' ' . self::getPossessiveMonthNamesArray($lastMonth);
        }        
        return $output;
    }
}