<?php
/**
 * Модель таблица даты. Даты принадлежат событию
 *
 * @author user
 */
class MfCalendarNetTable extends Zend_Db_Table_Abstract {

    protected $_name = 'mf_calendar_net';
    protected $_primary = 'id';
    // класс работы с одной строкой
    protected $_rowClass = 'MfCalendarNetRow';
    /**
     * правила зависимых таблиц
     */
    protected $_referenceMap    = array(
        'Object' => array (
            'columns' => array('event_id'),
            'refTableClass' => 'MfCalendarEventTable',
            'refColumns' => array('id')
        )
    );

    /**
     * получить список дат из списка событий
     * @todo переделать. Кажется это хуевая реализация
     */
    public static function getNetForEventList($events, $year) {
        $netArray = array();
        foreach ($events as $event) {
            foreach ($event->getDates($year) as $date) {
                $netArray[$date->getDate()][] = $event;
            }
        }
        return $netArray;
    }

    /**
     * получение сетки
     * 
     * @param <type> $year
     * @param <type> $month
     * @param <type> $day
     * @param <type> $eventIds
     * @return <type>
     */
    public function getNet($year, $month = '', $day = '', $eventIds = array()) {
        $select = $this->select();
        $select->where('year = ?', $year);
        if ($month) {
            $select->where('month = ?', $month);
        }
        if ($day) {
            $select->where('day = ?', $day);
        }
        if ($eventIds) {
            $select->where('event_id in (' . implode(',', $eventIds) . ')');
        }
        $select->order('full_date');
        
        return $this->fetchAll($select);
    }
}
?>
