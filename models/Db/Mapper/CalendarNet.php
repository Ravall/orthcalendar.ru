<?php
    require_once dirname(__FILE__) . '/Abstract.php';
    require_once PATH_BASE . '/models/Db/Gateway/CalendarNet.php';

    class Db_Mapper_CalendarNet extends Db_Mapper_Abstract {

        private $start = false;

        public function  __construct() {
            $this->setDbTable('Db_Gateway_CalendarNet');
        }

        #public function removeByEventId($eventId) {
        #    $this->getDbTable()->getAdapter()->query(
        #        'delete from mf_calendar_net where event_id = ' . $this->quote($eventId)
        #    );
        #}
        
        public function removeByFunctionId($functionId) {
            $this->getDbTable()->getAdapter()->query(
                'delete from mf_calendar_net where function_id = ' . $this->quote($functionId)
            );
        }
        

        public function addStart() {
            $this->start = true;
            $this->values = array();
        }

        /**
         * заполняет календарь событий
         * @param type $id
         * @param type $array 
         */
        public function addNet($id, $array) {            
            $values = array();
            $insertValues = array();
            foreach ($array as $key => $values) {
                    $value = '(' .
                        $this->quote($array[$key][0]) . ',' .
                        $this->quote($array[$key][1]) . ',' .
                        $this->quote($array[$key][2]) . ',' .
                        $this->quote(implode('-', array_reverse($array[$key]))) . ','.
                        $this->quote($id)
                     . ')';
              $insertValues[] = $value;
            }            
            if (!empty($insertValues)) {
                if ($this->start) {                
                    $this->values = array_merge($this->values, $insertValues);
                } else {
                    $this->insert($insertValues);
                }
            }

        }

        private function insert($values) {     
            $this->getDbTable()->getAdapter()->query(
                'INSERT INTO mf_calendar_net (day, month, year, full_date, function_id) VALUES ' . implode(',', $values)
            );            
        }

        public function addEnd() {
            $this->start = false;
            $this->insert($this->values);
            $this->values = array();
        }


        /**
         * получаем все события которые есть в заданном году
         * 
         * @param type $categoryId
         * @param type $year
         * @return type 
         */
        public function getEventListInYear($categoryId, $year) {
            $categoryId = (int) $categoryId;
            $year = (int) $year;            
            $sql =  'SELECT DISTINCT ev.id FROM mf_calendar_net net '
                  . ' JOIN mf_calendar_event ev ON ev.function_id = net.function_id '
                  . ' LEFT JOIN mf_system_object ob ON ev.id = ob.id  AND ob.parent_id = ' . $categoryId
                  . ' WHERE `year`= ' . $year
                  . ' ORDER BY net.full_date asc';            
            return $this->getDbTable()->getAdapter()->fetchCol($sql);
        }

        
        public function getEventListInDay($categoryId, $year, $month, $day, $periodic) {
            $categoryId = (int) $categoryId;
            $year = (int) $year;
            $month = (int) $month;
            $day = (int) $day;            
            $andSqlPeriodic = ($periodic!== false) ? 'AND ev.periodic = ' . $periodic : '';            
            $sql =  'SELECT DISTINCT ev.id FROM mf_calendar_net net '
                  . ' JOIN mf_calendar_event ev ON ev.function_id = net.function_id '. $andSqlPeriodic
                  . ' LEFT JOIN mf_system_object ob ON ev.id = ob.id  AND ob.parent_id = ' . $categoryId
                  . ' WHERE `day` = ' . $day . ' AND `month`= ' . $month . ' AND `year`= ' . $year
                  . ' ORDER BY net.full_date asc';            
            return $this->getDbTable()->getAdapter()->fetchCol($sql);
        }

        /**
         * получить дни, в которых есть события за сегодняшний месяц
         * необходимо для отрисовки календарика
         * 
         * @param type $year
         * @param type $month
         * @param type $ids
         * @return type 
         */
        public function getDaysInMonthWhereIdIn($year, $month, $ids) {            
            $year = (int) $year;
            $month = (int) $month;
            $sql = 'SELECT DISTINCT `day` FROM mf_calendar_net net '
                 . ' JOIN `mf_calendar_event` ev '
                 . ' ON net.`function_id` = ev.`function_id`WHERE '
                 . ' net.`year` = ' . $year . ' AND net.`month` = ' . $month 
                 . ' AND ev.id IN ('.  implode(',', $ids).')';
            return $this->getDbTable()->getAdapter()->fetchCol($sql);
        }


        
        private function getWhereSqlAdd($formDate) {
            $eventIdTodaySql = 'SELECT DISTINCT event.function_id FROM mf_calendar_net net '
                             . 'JOIN mf_calendar_event event ON event.function_id = net.function_id '
                             . 'WHERE net.full_date = "'.$formDate . '"';
            $eventIdToday = $this->getDbTable()->getAdapter()->fetchCol($eventIdTodaySql);            
            $sqlAdd = empty($eventIdToday) ? '' : ' AND net.function_id not in (' . implode(',', $eventIdToday) . ') ';
            return $sqlAdd;
        }

        /**
         * Получаем события которые будут через N дней вперед
         * @param <type> $categoryId
         * @param <type> $n
         * @return <type>
         */
        public function getEventsNextNDays($categoryId, $n, $formDate) {
            $n = (int) $n;
            $categoryId = (int) $categoryId;
            $sql = 'SELECT DISTINCT event.id FROM mf_calendar_net net '
                 . ' JOIN mf_calendar_smart_function fun ON fun.id = net.function_id '
                 . ' JOIN mf_calendar_event event ON event.function_id = fun.id'
                 . ' JOIN mf_system_object ob ON ob.id = event.id AND ob.parent_id = ' . $categoryId
                 . ' WHERE '
                 . ' `full_date` BETWEEN "' . $formDate . '" AND "' . $formDate . '" + INTERVAL ' . $n . ' DAY '
                 . $this->getWhereSqlAdd($formDate)
                 . ' ORDER BY full_date ';            
            return $this->getDbTable()->getAdapter()->fetchCol($sql);
        }

        /**
         * получаем limit записей но не выходящих в пределе одного года
         *
         * @param <type> $categoryId
         * @param <type> $limit
         * @return <type>
         */
        public function getEventsIdNextLimit($categoryId, $limit, $formDate) {
            $limit = (int) $limit;
            $categoryId = (int) $categoryId;
            $sql = 'SELECT DISTINCT event.id FROM mf_calendar_net net '
                 . ' JOIN mf_calendar_smart_function fun ON fun.id = net.function_id '
                 . ' JOIN mf_calendar_event event ON event.function_id = fun.id'
                 . ' JOIN mf_system_object ob ON ob.id = event.id AND ob.parent_id = ' . $categoryId
                 . ' WHERE '
                 . ' `full_date` BETWEEN "' . $formDate . '" AND "' . $formDate . '" + INTERVAL 1 YEAR'
                 . $this->getWhereSqlAdd($formDate)
                 . ' ORDER BY full_date LIMIT '. $limit;
            
            return $this->getDbTable()->getAdapter()->fetchCol($sql);
        }


         /**
         * Получаем события которые были с сегодня до N дней назад, не включая тех что были сегодня
         * @param <type> $categoryId
         * @param <type> $n
         * @return <type>
         */
        public function getEventsPrevNDays($categoryId, $n, $formDate) {
            $n = (int) $n;
            $categoryId = (int) $categoryId;
            
            $sql = 'SELECT DISTINCT event.id FROM mf_calendar_net net '
                 . ' JOIN mf_calendar_smart_function fun ON fun.id = net.function_id '
                 . ' JOIN mf_calendar_event event ON event.function_id = fun.id'
                 . ' JOIN mf_system_object ob ON ob.id = event.id AND ob.parent_id = ' . $categoryId
                 . ' WHERE '
                 . ' `full_date` BETWEEN  "' . $formDate . '" - INTERVAL ' . $n . ' DAY AND "' . $formDate . '"'
                 . $this->getWhereSqlAdd($formDate)
                 . ' ORDER BY full_date desc';       
            
            return $this->getDbTable()->getAdapter()->fetchCol($sql);
        }

        /**
         * Получаем события которые были с сегодня декабря прошлого года
         * @param <type> $categoryId
         * @param <type> $n
         * @return <type>
         */
        public function getEventsPrevForYear($categoryId, $formDate) {            
            $categoryId = (int) $categoryId;
            list($year,$month,$day) = explode('-', $formDate);
            $lastyear = $year-1;
            $sql = 'SELECT DISTINCT net.event_id,net.month,net.year FROM mf_calendar_net net'
                 . ' JOIN mf_system_object ob ON ob.id = net.event_id AND ob.parent_id = ' . $categoryId
                 . ' WHERE net.event_id not in (select DISTINCT event_id FROM mf_calendar_net WHERE full_date = "' . $formDate . '") '
                 . ' AND `full_date` BETWEEN "' . $lastyear . '-12-31" AND " '. $formDate .'"'
                 . ' ORDER BY full_date desc';            
            return $this->getDbTable()->getAdapter()->fetchAll($sql);
        }

        /**
         * Получаем N прошедших событий в диапазоне от сегодня до года назад
         * @param <type> $categoryId
         * @param <type> $n
         * @return <type>
         */
        public function getEventsPrevNLimit($categoryId, $n, $formDate) {
            $n = (int) $n;
            $categoryId = (int) $categoryId;
            
             $sql = 'SELECT DISTINCT event.id FROM mf_calendar_net net '
                 . ' JOIN mf_calendar_smart_function fun ON fun.id = net.function_id '
                 . ' JOIN mf_calendar_event event ON event.function_id = fun.id'
                 . ' JOIN mf_system_object ob ON ob.id = event.id AND ob.parent_id = ' . $categoryId
                 . ' WHERE '
                 . ' `full_date` BETWEEN "' . $formDate . '" AND "' . $formDate . '" + INTERVAL 1 YEAR'
                 . $this->getWhereSqlAdd($formDate)
                 . ' ORDER BY full_date desc LIMIT '. $n;
             
            return $this->getDbTable()->getAdapter()->fetchCol($sql);
        }

        
        /**
         * получает список ремарков, которые есть на сегодня
         * 
         * @param type $year
         * @param type $month
         * @param type $day
         * @return type 
         */
        public function getRemarksByDay($year, $month, $day) {
             $sql = 'SELECT DISTINCT remark.id FROM mf_calendar_net net 
                     JOIN mf_calendar_smart_function fun ON fun.id = net.function_id
                     JOIN mf_calendar_remark remark ON remark.function_id = fun.id
                     WHERE net.day=' . $day . ' AND net.month = ' . $month 
                     . ' AND net.year = ' . $year;
             
             return $this->getDbTable()->getAdapter()->fetchCol($sql);
        }





    }
?>