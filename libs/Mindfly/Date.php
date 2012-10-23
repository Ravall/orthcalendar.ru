<?php
/**
 * Description of Date
 *
 * @author vsemenov
 */
class Mindfly_Date extends DateTime{
    

    private $timestamp;
    private $day;
    private $month;
    private $year;
    private $weekName = array('0' => 'Пн', '1' => 'Вт', '2'=>'Ср',
                              '3'=>'Чт', '4'=>'Пт', '5'=>'Сб', '6'=>'Вс');

    private static $monthName = array(
        'ru' => array(
            'full' => array(
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
                '12' => 'Декабрь'
            ),
            'possessive' => array(
                '01' => 'января',
                '02' => 'февраля',
                '03' => 'марта',
                '04' => 'апреля',
                '05' => 'мая',
                '06' => 'июня',
                '07' => 'июля',
                '08' => 'августа',
                '09' => 'сентября',
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


    public function  __construct($date = null) {      
        // если дата поступила неполная
        if (preg_match('/^(\d{4})-(\d{2})$/', $date, $array)) {
            $this->day = false;
            $temp = new Mindfly_Date($date . '-01');
            $this->month = $temp->getM();
            $this->year = $temp->getY();
        } else {
            $this->timestamp = $date == null ? time() : strtotime($date);
            $this->day = date('d', $this->timestamp);
            $this->month = date('m', $this->timestamp);
            $this->year = date('Y', $this->timestamp);
        }
    }


    public static function getFullMonthNamesArray($lang = LANG_DEFAULT)
    {
        $array = self::$monthName;
        return  $array[$lang]['full'];
    }

    public static function getShortMonthNamesArray($lang = LANG_DEFAULT)
    {
        $array = self::$monthName;        
        return $array[$lang]['short'];
    }

    public static function getPossessiveMonthNamesArray($lang = LANG_DEFAULT)
    {
        $array = self::$monthName;
        return $array[$lang]['possessive'];
    }

    public static function getPrepositionalMonthNamesArray($lang = LANG_DEFAULT)
    {
        $array = self::$monthName;
        return $array[$lang]['prepositional'];
    }

    public static function parseDateArrayToString($array) {
        if (count($array) == 1) {
            return $array[0];
        }
        sort($array);
        $string = $last = $array[0];
        for ($i=1; $i<$lastIndex = count($array)-1; $i++)
        {
            list($preDt, $dt, $postDt) = array($array[$i-1], $array[$i], $array[$i+1]);
            if ($preDt+1 == $dt && $dt+1 == $postDt) {
                continue;
            }
            $string.= (($last+1 != $dt && $preDt +1 == $dt) ? '-' : ',') . $dt;
            $last = $postDt;
        }
        if ($last+1 != $array[$lastIndex] && $array[$lastIndex-1] +1 == $array[$lastIndex]) {
            $string.=' &mdash; '.$array[$lastIndex];
        } else {
            $string.=','.$array[$lastIndex];
        }
        return  $string;
    }

    /**
     * получаем пустую карту событий месяца
     *
     * @return int
     */
    public function getEmptyMonthMap() {
        $date = $this->getMoth();
        $map = array();
        for (${0}=$date->getFrom();${0}<=$date->getTo();${0}++) {
            $map[${0}] = 0;
        }
        return $map;
    }

    /**
     * получение года
     * 
     * @return DateResult
     */
    public function getYear() {
        return new DateResult(date('Y-01-01', $this->timestamp), date('Y-12-31', $this->timestamp));
    }

    /**
     * получение месяца
     *
     * @return DateResult
     */
    public function getMoth() {
        return new DateResult(date('Y-m-01', $this->timestamp), date('Y-m-t', $this->timestamp));
    }

    /**
     * получения дня
     */
    public function getDay() {
        return date('Y-m-d', $this->timestamp);
    }

    public function getD() {
        return $this->day;
    }

    public function getM() {
        return $this->month;
    }

    public function getY() {
        return $this->year;
    }

    public function getW() {
        return $this->weekName[date('w', $this->timestamp)];
    }


    /**
     * получаем дату с указанием формата
     *
     * @param <type> $format
     * @return <type>
     */
    public function getDateByFormat($format) {
        return date($format, $this->timestamp);
    }
    
    /**
     * изменяет дату на следующий день
     */
    public function nextDay() {
        $this->timestamp = strtotime(date('Y-m-d',$this->timestamp).'+1 day');
        $this->day = date('d', $this->timestamp);
        $this->month = date('m', $this->timestamp);
        $this->year = date('Y', $this->timestamp);
        return $this;
    }

    /**
     * Получает 1 число следующего месяца
     */
    public function getNextMonth() {
        $timestamp = strtotime(date('Y-m-d',$this->timestamp).'+1 month');
        return new Mindfly_Date(date('Y-m-01', $timestamp));
    }

    /**
     * Получает 1 число предыдущего месяца
     */
    public function getPrevMonth() {
        $timestamp = strtotime(date('Y-m-d',$this->timestamp).'-1 month');
        return new Mindfly_Date(date('Y-m-01', $timestamp));
    }

    /**
     * Получает последнее число предыдущего месяца
     */
    public function getLastDayPrevMonth() {
        $timestamp = strtotime(date('Y-m-d',$this->timestamp).'-1 month');
        return new Mindfly_Date(date('Y-m-t', $timestamp));
    }

    /**
     * Получает следущий день
     */
    public function getNextDay() {
        $timestamp = strtotime(date('Y-m-d',$this->timestamp).'+1 day');
        return new Mindfly_Date(date('Y-m-d', $timestamp));
    }

    /**
     * Получает предыдущий день
     */
    public function getPrevDay() {
        $timestamp = strtotime(date('Y-m-d',$this->timestamp).'-1 day');
        return new Mindfly_Date(date('Y-m-d', $timestamp));
    }

    /**
     * $whithYear = true Возвращает дату в формате 1 января, 2010
     * $whithYear = false Возвращает дату в формате 1 января
     * @return <type>
     * @todo нужен тест
     */
    public function getFormatDay($whithYear = true) {
        $array = self::getPossessiveMonthNamesArray();
        $date = (int) $this->getD() . ' ' .$array[$this->getM()];
        if ($whithYear) {
            $date .= ' ' . $this->getY();
        }
        return $date;
    }
    
    /**
     * проверка
     * выбранный день это сегодня или нет.
     */
    public function isToday() {        
        $today = new self();        
        return (bool) ($this->getDay() === $today->getDay());
    }



}


class DateResult {
    private $from;
    private $to;

    public function  __construct($from,$to) {
        $this->from = $from;
        $this->to = $to;
    }
    public function getFrom() {
        return $this->from;
    }

    public function getTo() {
        return $this->to;
    }
}