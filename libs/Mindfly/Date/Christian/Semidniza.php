<?php
require_once PATH_LIBS . '/Mindfly/Date.php';
require_once PATH_LIBS . '/Mindfly/Date/Christian/Days.php';

/**
 * Сплошных седмиц пять:
 * Святки - с 7 января до 19 января.
 * Мытаря и фарисея - за 2 недели до Великого Поста.
 * Сырная (масленица - народн.) - неделя перед Великим Постом (но без мяса).
 * Пасхальная (светлая) - неделя после Пасхи.
 * Троицкая - неделя после Троицы.
 *
 * -----------------
 * сопутсвующие дни Фомино воскресенье (антипасха). <- конец пасхальной недели
 *
 * @author user
 */
class Mindfly_Date_Christian_Semidniza {

    /**
     * Святки. Поста нет.
     */
    public function christmasWeek($year) {
        $data = array();
        $time = new Mindfly_Date($year . '-01-06');
        while ($time->getDay() != $year . '-01-18') {
            $time->nextDay();
            $data[] = $time->getDay();
        }
        return $data;
    }

    /**
     * семидница после Неделя Мытаря и фарисея
     * @param <type> $year
     */
    public function taxCollectorAndPhariseeWeek($year) {
        $data = array();
        $lentBegin = Mindfly_Date_Christian_Fast::lentFastBegin($year);
        $time = new Mindfly_Date(
            date(DATE_SYSTEM_FORMAT_SHORT, strtotime($lentBegin . ' - 3 week -1 day'))
        );
        for ($i=0; $i<7; $i++) {
            $time->nextDay();
            $data[] = $time->getDay();            
        }
        return $data;
    }

    /**
     * Масленица воспрещается есть мясо
     */
    public function carnivalWeek($year) {
        $data = array();
        $easter = Mindfly_Date_Christian_GreatFeasts::orthodoxyEasterDay($year);
        $time = new Mindfly_Date(date(DATE_SYSTEM_FORMAT_SHORT, strtotime($easter.' - 8 week')));        
        for ($i=0; $i<7; $i++) {
            $time->nextDay();
            $data[] = $time->getDay();
        }
        return $data;
    }

    /**
     * Пасхальная неделя
     */
    public function eastertideWeek($year) {
        $data = array();
        $easter = Mindfly_Date_Christian_GreatFeasts::orthodoxyEasterDay($year);
        $time = new Mindfly_Date(date(DATE_SYSTEM_FORMAT_SHORT, strtotime($easter)));
        for ($i=0; $i<7; $i++) {
            $time->nextDay();
            $data[] = $time->getDay();            
        }
        return $data;
    }

    /**
     * Троицкая неделя
     * неделя перед петровым постом
     */
    public function whitsunWeek($year) {
        $data = array();
        $fast = Mindfly_Date_Christian_Fast::apostlesFastBegin($year);
        $time = new Mindfly_Date(date(DATE_SYSTEM_FORMAT_SHORT, strtotime($fast.' - 1 week -1 day')));
        for ($i=0; $i<7; $i++) {
            $time->nextDay();
            $data[] = $time->getDay();
         
        }
        return $data;        
    }
 
    /**
     * мясопустная семидица
     * пост в среду и пятницу
     * 
     * @param <type> $year
     */
    public function sexagesima($year) {
        $data = array();
        $lentBegin = Mindfly_Date_Christian_Fast::lentFastBegin($year);
        $time = new Mindfly_Date(
            date(DATE_SYSTEM_FORMAT_SHORT, strtotime($lentBegin . ' - 2 week -1 day'))
        );
        for ($i=0; $i<7; $i++) {
            $time->nextDay();
            $data[] = $time->getDay();            
        }
        return $data;
    }

    /**
     * Страстная седмица
     * 6 дней. 7-ое пасха
     */
    public function  passionWeek($year) {
        $data = array();
        $lentBegin = Mindfly_Date_Christian_Fast::lentFastBegin($year);
        $time = new Mindfly_Date(
            date(DATE_SYSTEM_FORMAT_SHORT, strtotime($lentBegin . ' + 6 week -1 day'))
        );
        for ($i=0; $i<6; $i++) {
            $time->nextDay();
            $data[] = $time->getDay();
        }
        return $data;
    }
}