<?php
require_once PATH_LIBS . '/Mindfly/Date.php';
/**
 * Дни страстной седмицы
 */
class Mindfly_Date_Christian_PassionSedmiza {

    /**
     * понедельик
     * 
     * @param <type> $year
     * @return <type>
     */
    public static function monday($year) {
        $passion = Mindfly_Date_Christian_Semidniza::passionWeek($year);
        return $passion[0];
    }

    /**
     * вторник
     *
     * @param <type> $year
     * @return <type>
     */
    public static function tuesday($year) {
        $passion = Mindfly_Date_Christian_Semidniza::passionWeek($year);
        return $passion[1];
    }

    /**
     * среда
     *
     * @param <type> $year
     * @return <type>
     */
    public static function wednesday($year) {
        $passion = Mindfly_Date_Christian_Semidniza::passionWeek($year);
        return $passion[2];
    }

    /**
     * четверг
     *
     * @param <type> $year
     * @return <type>
     */
    public static function thursday($year) {
        $passion = Mindfly_Date_Christian_Semidniza::passionWeek($year);
        return $passion[3];
    }

    /**
     * пятница
     *
     * @param <type> $year
     * @return <type>
     */
    public static function friday($year) {
        $passion = Mindfly_Date_Christian_Semidniza::passionWeek($year);
        return $passion[4];
    }

    /**
     * суббота
     *
     * @param <type> $year
     * @return <type>
     */
    public static function saturday($year) {
        $passion = Mindfly_Date_Christian_Semidniza::passionWeek($year);
        return $passion[5];
    }


}
?>
