<?php
/**
 * Строка таблицы дата. Каждая дата принадлежит событию
 *
 * @author user
 */
class MfCalendarNetRow extends Zend_Db_Table_Row_Abstract {
    
    /**
     * сохраняем дату по частям. Для лучшего поиска
     * @return <type>
     */
    public function  save() {        
        $date = new Mindfly_Date($this->full_date);
        $this->day = $date->getD();
        $this->month = $date->getM();
        $this->year = $date->getY();
        return parent::save();
    }

    public function getDate($raw = false) {
        $date = new Mindfly_Date($this->full_date);
        if ($raw) {
            return $date;
        }
        return $date->getDay();
    }
}
?>
