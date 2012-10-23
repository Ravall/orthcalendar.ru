<?php
require_once PATH_LIBS . '/Mindfly/Date/Christian/Fast.php';
/* 
 * Дни четырдясятныцы
 * 
 */
class Mindfly_Date_Christian_FortyDayFast {

    public function  __construct($year) {
        $this->lentBegin = Mindfly_Date_Christian_Fast::lentFastBegin($year);
    }

   /*
    * Неделя мытаря и фарисея.
    * воскресенье
    */
   public function sundayOfThePublicanAndPharisee() {        
        return $this->getDay(' - 3 week -1 day');
   }

   /**
    *  неделя о блудном сыне
    */
   public function septuagesima() {        
        return $this->getDay(' - 2 week -1 day');
   }

   /**
     * Мясопустная вселенская родительская суббота
     * - за неделю до Великого поста.
     *
     * @param <type> $year
     */
    public function meatSaturdayRemembranceParents() {         
         return $this->getDay(' -1 week last saturday');
    }

    /**
     * Неделя о Страшном суде. Заговенье на мясо
     */
    public function sundayOfTheJudgementDay() {         
         return $this->getDay(' -1 week last sunday');
    }

    /**
     * Прощеное воскресенье - Неделя сыропустная
     */
    public function quinquagesima() {        
        return $this->getDay(' -1 day');
    }

    /* ВЕЛИКИЙ ПОСТ */

    /**
     * тожество православия
     * 
     * @return <type>
     */
    public function feastOfOrthodoxy() {         
         return $this->getDay(' next sunday');
    }

    
    /**
     * Родительская вселенская суббота -2,3,4 недели Великого поста
     *
     * @param <type> $year
     */
    public function saturdayRemembranceParents() {       
         $days[] = $this->getDay(' + 1 week next saturday');
         $days[] = $this->getDay(' + 2 week next saturday');
         $days[] = $this->getDay(' + 3 week next saturday');
         return $days;
    }


    /**
     * Неделя 2-я Великого поста - Святителя Григория Паламы
     * @return <type>
     */
    public function gregoryPalamas() {
        return $this->getDay(' +1 week next sunday');
    }

    /**
     * Неделя 3-я Великого поста - Крестопоклонная
     * 
     * @return <type>
     */
    public function sundayOfTheVenerationOfTheCross() {
        return $this->getDay(' +2 week next sunday');
    }


    /**
     * Неделя 4-я Великого поста -
     * Преподобного Иоанна Лествичника
     */
    public function johnClimacus() {
        return $this->getDay(' +3 week next sunday');
    }

    /**
     * Марьино стояние
     *   мария египетская и
     *   на утрене читается весь великий покаянный канон Андрея Критского,
     * 
     * @return <type>
     */
    public function stationsMaryTheEgyptian() {
       return $this->getDay(' +4 week next thursday');
    }


    /**
     * Суббота акафиста
     */
    public function akathistusSaturday() {
        return $this->getDay(' +4 week next saturday');
    }

    /**
     * Неделя 5-я Великого поста - Преподобной Марии Египетской
     *
     * @return <type>
     */
    public function maryTheEgyptian() {
       return $this->getDay(' +4 week next sunday');
    }

     /**
     * Лазарева суббота
     *
     * @param <type> $year
     */
    public function lazarusSaturday() {
        return $this->getDay(' + 5 week +5 day');
    }



    /*----------------------------*/

    private function getDay($offset) {
        $data = new Mindfly_Date(
             date(DATE_SYSTEM_FORMAT_SHORT, strtotime(($this->lentBegin . $offset)))
        );
        return $data->getDay();
    }
}

