<?php
require_once PATH_LIBS . '/Mindfly/Date.php';
require_once PATH_LIBS . '/Mindfly/Date/Christian/Days.php';
require_once PATH_LIBS . '/Mindfly/Date/Christian/GreatFeasts.php';
require_once PATH_LIBS . '/Mindfly/Date/Christian/Semidniza.php';
require_once PATH_LIBS . '/Mindfly/Date/Christian/Fast.php';

/**
 * Мясоед
 * Время когда разрешается есть мясо, кроме сред и пятниц
 * однодневных постов и сплошных семидиц
 *
 * весенний мясоед
 * летний мясоед
 * осенний мясоед
 * зимний мясоед
 *
 * @author user
 */
class Mindfly_Date_Christian_Meatfare {
    /**
     * Осенний мясоед
     * по средам и пятницам - сухоядение,
     * кроме Усекновения главы Иоанна Предтечи и Воздвижение Креста Господня
     */
    public function autumn($year) {
        $data = array();
        $time = new Mindfly_Date(Mindfly_Date_Christian_Fast::assumptionFastEnd($year));
        $time->nextDay();        
        do {            
            $data[] = $time->getDay();
            $time->nextDay();
        } while ($time->getDay() != Mindfly_Date_Christian_Fast::adventFastBegin($year));
        return $data;
    }

    /**
     * Зимний мясоед с рожденственского поста по великий пост
     * по средам и пятницам - рыба
     *
     * кроме Крещенский сочельник
     * семидница: Святки, Мытаря и фарисея, масленица
     * @param <type> $year
     */
    public function winter($year) {
        $data = array();
        $time = new Mindfly_Date (Mindfly_Date_Christian_Fast::adventFastEnd($year-1));
        $time->nextDay();        
        //day
        $theEveOfTheophany = Mindfly_Date_Christian_Days::theEveOfTheophany($year);
        do {            
            $data[] = $time->getDay();
            $time->nextDay();
        } while ($time->getDay() != Mindfly_Date_Christian_Fast::lentFastBegin($year));
        return $data;
    }

    /**
     * Весенний мясоед
     * c окончания великого поста до начала петрова поста
     * по средам и пятницам - рыба
     * кроме Пасхальной недели, троицкой недели
     * @param <type> $year
     */
    public function spring($year) {
        $data = array();
        $time = new Mindfly_Date (Mindfly_Date_Christian_Fast::lentFastEnd($year));
        $time->nextDay();        
        do {
            $data[] = $time->getDay();
            $time->nextDay();
        } while ($time->getDay() != Mindfly_Date_Christian_Fast::apostlesFastBegin($year));
        return $data;
    }

    /**
     * Летний мясоед
     * конец петрова поста - день апостолов
     * да начала успенского поста
     */
    public function summer($year) {
        $data = array();
        $time = new Mindfly_Date (Mindfly_Date_Christian_Fast::apostlesFastEnd($year));
        $time->nextDay();        
        do {                        
            $data[] = $time->getDay();
            $time->nextDay();
        } while ($time->getDay() != Mindfly_Date_Christian_Fast::assumptionFastBegin($year));
        return $data;
    }
}
