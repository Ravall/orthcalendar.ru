<?php
require_once PATH_LIBS . '/Mindfly/Date.php';
/**
 * Великие праздники
 *
 * Пасха
 * Рождество Богородицы — 8 (21) сентября;
 * Воздвижение Креста Господня — 14 (27) сентября;
 * Введение во храм Пресвятой Богородицы — 21 ноября (4 декабря);
 * Рождество Христово — 25 декабря (7 января);
 * Крещение Господне — 6 (19) января;
 * Сретение Господне — 2 (15) февраля;
 * Благовещение Пресвятой Богородицы — 25 марта (7 апреля);
 * Вход Господень в Иерусалим (переходящий) — воскресенье перед Пасхой;
 * Вознесение Господне (переходящий) — 40-й день после Пасхи, всегда в четверг;
 * День Святой Троицы (переходящий) — 50-й день после Пасхи, всегда в воскресенье;
 * Преображение Господне — 6 (19) августа;
 * Успение Богородицы — 15 (28) августа.
 *
 *
 * Покров Пресвятой Богородицы — 1 (14) октября;
 * Обрезание Господне и память свт. Василия Великого[3] — 1 (14) января;
 * Рождество Иоанна Предтечи — 24 июня (7 июля);
 * День святых первоверховных апостолов Петра и Павла — 29 июня (12 июля);
 * Усекновение главы Иоанна Предтечи — 29 августа (11 сентября).
 *
 * Кроме того, в дониконовских (и в современных старобрядческих)
 * уставах к великим праздникам относились также дни памяти апостола Иоанна Богослова
 * (26 сентября, 8 мая), преподобного Сергия Радонежского (25 сентября) и
 * все переходящие двунадесятые праздники.
 *
 */
class Mindfly_Date_Christian_GreatFeasts {



    /**
     * расчет католической пасхи
     * @return <type>
     */
    public static function catholicEasterDay($year) {
        #First calculate the date of easter using Delambre's algorithm.
        $a = $year % 19;
        $b = floor($year / 100);
        $c = $year % 100;
        $d = floor($b / 4);
        $e = $b % 4;
        $f = floor(($b + 8) / 25);
        $g = floor(($b - $f + 1) / 3);
        $h = (19 * $a + $b - $d - $g + 15) % 30;
        $i = floor($c / 4);
        $k = $c % 4;
        $l = (32 + 2 * $e + 2 * $i - $h - $k) % 7;
        $m = floor(($a + 11 * $h + 22 * $l) / 451);
        $n = ($h + $l - 7 * $m + 114);
        $month = floor($n / 31);
        $day = $n % 31 + 1;
        return $year.'-'.sprintf('%02s',$month).'-'.sprintf('%02s',$day);
    }

    /**
     * расчет православной пасхи
     *
     * @param <type> $year
     */
    public static function orthodoxyEasterDay($year) {
         $a = (19 * ($year % 19) + 15) % 30;
         $b = (2 * ($year % 4) + 4* ($year % 7) + 6 * $a +6) % 7;
         if ($a + $b > 10) {
             $d = $a + $b - 9;
             $d = sprintf('%02s',$d);
             $m = '04';
         } else {
             $d = 22 + $a + $b;
             $m = '03';
             $d = sprintf('%02s',$d);
         }
         $date = $year .'-' . $m . '-' . $d;
         return date(DATE_SYSTEM_FORMAT_SHORT, strtotime($date . " + 13 day"));
    }

    /**
     * Рождество Богородицы
     */
    public static function nativityOfTheBlessedVirgin($year) {
        $time = new Mindfly_Date($year . '-09-21');
        return $time->getDay();
    }

    /**
     * Водвижение креста господня
     *
     * @param <type> $year
     */
    public static function exaltationOfTheCross($year) {
        $time = new Mindfly_Date($year . '-09-27');
        return $time->getDay();
    }

    /**
     * Введение во храм Пресвятой Богородицы
     * @param <type> $year
     */
    public static function presentationOfTheTheotokos($year) {
        $time = new Mindfly_Date($year . '-12-04');
        return $time->getDay();
    }

    /**
     * Рождество христово
     *
     * @param <type> $year
     */
    public function christmas($year) {
        $time = new Mindfly_Date($year . '-01-07');
        return $time->getDay();
    }

    /**
     * Крещение Господне
     * @param <type> $year 
     */
    public static function theBaptismOfOurLord($year) {
        $time = new Mindfly_Date($year . '-01-19');
        return $time->getDay();
    }

    /**
     * Сретение Господне
     * @param <type> $year
     */
    public static function thePresentationOfTheLordInTheTemple($year) {
        $time = new Mindfly_Date($year . '-02-15');
        return $time->getDay();
    }

    /**
     * Благовещение Пресвятой Богородицы
     * 
     * @param <type> $year
     * @return <type>
     */
    public static function feastOfTheAnnunciation($year) {
        $time = new Mindfly_Date($year . '-04-07');
        return $time->getDay();
    }

    /**
     * Вход Господень в Иерусалим
     *
     * @param <type> $year
     */
    public static function theEntryOfOurLordIntoJerusalem($year) {
         $easter = self::orthodoxyEasterDay($year);
         $data = new Mindfly_Date(date(DATE_SYSTEM_FORMAT_SHORT, strtotime($easter . ' last sunday')));
         return $data->getDay();
    }

    /**
     * Вознесение Господне
     * @param <type> $year
     */
    public static function theAscensionOfChrist($year) {
         $easter = self::orthodoxyEasterDay($year);
         $data = new Mindfly_Date(date(DATE_SYSTEM_FORMAT_SHORT, strtotime($easter.' + 39 day')));
         return $data->getDay();
    }

    /**
     * День святой троицы
     */
    public static function whitsundayDay($year) {
         $easter = self::orthodoxyEasterDay($year);
         $data = new Mindfly_Date(date(DATE_SYSTEM_FORMAT_SHORT, strtotime($easter.' + 49 day')));
         return $data->getDay();
    }

    /**
     * Преображение Господне
     * @param <type> $year
     */
    public static function theHolyTransfigurationOfOurLordJesusChrist($year) {
        $time = new Mindfly_Date($year . '-08-19');
        return $time->getDay();
    }

    /**
     *  Успение Пресвятой Богородицы
     */
    public static function dormitionOfTheTheotokos($year) {
        $time = new Mindfly_Date($year . '-08-28');
        return $time->getDay();
    }

    /**
     * Покров Пресвятой Богородицы
     * 
     * @param <type> $year
     * @return <type>
     */
    public static function theVirginOfMercy($year) {
        $time = new Mindfly_Date($year . '-10-14');
        return $time->getDay();
    }

    /**
     * Обрезание Господне
     * 
     * @param <type> $year
     * @return <type>
     */
    public static function theCircumcisionOfChrist($year) {
        $time = new Mindfly_Date($year . '-01-14');
        return $time->getDay();
    }

    /**
     * Рождество Иоанна Предтечи
     *
     * @param <type> $year
     */
    public static function theNativityOfStJohntheBaptist($year) {
        $time = new Mindfly_Date($year . '-07-07');
        return $time->getDay();
    }

    /**
     * День апостолов петра и павла
     *
     * @param <type> $year
     * @return <type>
     */
    public static function peterAndPavelDay($year) {
        $time = new Mindfly_Date($year . '-07-12');
        return $time->getDay();
    }

    /**
     * Усекновение главы иона Предтечи
     * 
     * @param <type> $year
     */
    public static function beheadingOfStJohnTheBaptist($year) {
        $time = new Mindfly_Date($year . '-09-11');
        return $time->getDay();
    }






  




}