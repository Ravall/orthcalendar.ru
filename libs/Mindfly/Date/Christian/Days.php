<?php
require_once PATH_LIBS . '/Mindfly/Date.php';

class Mindfly_Date_Christian_Days {

    /**
     *	Рождественский сочельник
     * 
     * @param <type> $year
     * @return <type> 
     */
    public static function theEveOfTheophany($year) {
        $time = new Mindfly_Date($year . '-01-06');
        return $time->getDay();
    }


    /**
     *	Богоявленский сочельник
     *
     * @param <type> $year
     * @return <type>
     */
    public static function theEveOfEpiphany($year) {
        $time = new Mindfly_Date($year . '-01-18');
        return $time->getDay();
    }

     /**
     * Радоница
     *
     * @param <type> $year
     */
    public static function dayOfRejoicing($year) {
         $easter = Mindfly_Date_Christian_GreatFeasts::orthodoxyEasterDay($year);
         $data = new Mindfly_Date(date(DATE_SYSTEM_FORMAT_SHORT, strtotime($easter.' + 9 day')));
         return $data->getDay();
    }

    /**
     * 9-го мая - день поминовения всех погибших в годы Великой Отечественной войны
     *
     * @param <type> $year
     */
    public static function victoryDay($year) {
        $data = new Mindfly_Date($year . '-05-09');
        return $data->getDay();
    }

    /**
     *  Троицкая вселенская родительская суббота - суббота перед днем Святой Троицы.
     */
    public static function trinitySaturdayRemembranceParents($year) {
         $easter = Mindfly_Date_Christian_GreatFeasts::orthodoxyEasterDay($year);
         $data = new Mindfly_Date(date(DATE_SYSTEM_FORMAT_SHORT, strtotime($easter.' + 48 day')));
         return $data->getDay();
    }

    /**
     *  Димитриевская суббота - суббота за неделю перед праздником памяти
     *  великомученика Димитрия Солунского (8 ноября по новому стилю)
     */
    public static function dmitrySaturdayRemembranceParents($year) {
         $data = new Mindfly_Date(date(DATE_SYSTEM_FORMAT_SHORT, strtotime($year.'-11-08 last saturday')));
         return $data->getDay();
    }

   

 

}
?>
