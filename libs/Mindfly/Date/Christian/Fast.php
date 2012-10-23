<?php
require_once PATH_LIBS . '/Mindfly/Date.php';
require_once PATH_LIBS . '/Mindfly/Date/Christian/GreatFeasts.php';
/**
 *  православные посты
 */

/**
 * Description of ChristianPeriod
 *
 * @author user
 */
class Mindfly_Date_Christian_Fast {

    /**
     * Рождественский пост
     * 
     * @param <type> $setOfRemarks
     * @param <type> $year
     * @return <type>
     */
    public function advent($year) {
        $days = array();
        $time = new Mindfly_Date(self::adventFastBegin($year));
        // до 20 декабря        
        while ($time->getDateByFormat('d.m') != '06.01') {            
            $days[] = $time->getDay();
            $time->nextDay();
        }
        $days[] = $time->getDay();
        return $days;
    }

    /**
     * Успенский пост
     *
     * @param <type> $year
     */
    public function assumption($year) {
        $days = array();
        $time = new Mindfly_Date(self::assumptionFastBegin($year));
        $assumptionFastEnd = self::assumptionFastEnd($year);
        do {            
            $days[] = $time->getDay();
            $time->nextDay();
        } while ($time->getDay() != $assumptionFastEnd);
        $days[] = $time->getDay();
        return $days;
    }


    /**
     * Петров пост
     *
     * @param <type> $setOfRemarks
     * @param <type> $year
     * @return <type>
     */
    public function apostels($year) {
        $days = array();
        $time = new Mindfly_Date(self::apostlesFastBegin($year));
        $peterAndPavelDay = Mindfly_Date_Christian_GreatFeasts::peterAndPavelDay($year);
        do {            
            $days[] = $time->getDay();
            $time->nextDay();
        } while ($time->getDay() != $peterAndPavelDay);
        return $days;
    }


    /**
     * Великий пост
     * 
     * @param <type> $setOfRemarks
     * @param <type> $year
     * @return <type>
     */
    public function lent($year) {
        $days = array();
        $time = new Mindfly_Date(self::lentFastBegin($year));
        $orthodoxyEasterDay = Mindfly_Date_Christian_GreatFeasts::orthodoxyEasterDay($year);
        do {          
            $days[] = $time->getDay();
            $time->nextDay();
        } while ($time->getDay() != $orthodoxyEasterDay);
        return $days;
    }


    /**
     * день начала Петров Пост
     */
    public static function apostlesFastBegin($year) {
        $whitsunday = Mindfly_Date_Christian_GreatFeasts::whitsundayDay($year);
        $data = new Mindfly_Date(
            date(DATE_SYSTEM_FORMAT_SHORT, strtotime($whitsunday . ' + 8 day'))
        );
        return $data->getDay();
    }
    
    /**
     * день конца Петрова поста
     *
     * @param <type> $year
     * @return <type>
     */
    public static function apostlesFastEnd($year) {
        return $year . '-07-11';
    }


    /**
     * начало великого поста
     */
    public static function lentFastBegin($year) {
        $easter = Mindfly_Date_Christian_GreatFeasts::orthodoxyEasterDay($year);
        $carnival = new Mindfly_Date(date(DATE_SYSTEM_FORMAT_SHORT, strtotime($easter.' - 7 week')));
        $data = new Mindfly_Date(
            date(DATE_SYSTEM_FORMAT_SHORT, strtotime($carnival->getDay() . ' + 1 day'))
        );
        return $data->getDay();
    }

    /**
     * конец великого поста
     */
    public static function lentFastEnd($year) {
        $easter = Mindfly_Date_Christian_GreatFeasts::orthodoxyEasterDay($year);
        $data = new Mindfly_Date(
            date(DATE_SYSTEM_FORMAT_SHORT, strtotime($easter . ' - 1 day'))
        );
        return $data->getDay();
    }

    /**
     * начало успенского поста
     *
     * @param <type> $year
     * @return <type>
     */
    public static function assumptionFastBegin($year) {
        return $year . '-08-14';
    }

    /**
     * конец успенского поста
     *
     * @param <type> $year
     * @return <type>
     */
    public static function assumptionFastEnd($year) {
        return $year . '-08-27';
    }

    /**
     * начало рождественского поста
     *
     * @param <type> $year
     */
    public static function adventFastBegin($year) {
        $time = new Mindfly_Date($year . '-11-28');
        return $time->getDay();
    }

    /**
     * конец рождественского поста
     *
     * @param <type> $year
     */
    public static function adventFastEnd($year) {
        $time = new Mindfly_Date($year+1 . '-01-06');
        return $time->getDay();
    }


}
?>
