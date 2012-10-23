<?php
require_once PATH_LIBS . '/Mindfly/Date/Christian/Fast.php';
require_once PATH_LIBS . '/Mindfly/Date/Christian/Semidniza.php';
require_once PATH_LIBS . '/Mindfly/Date/Christian/GreatFeasts.php';
require_once PATH_LIBS . '/Mindfly/Date/Christian/Days.php';
require_once PATH_LIBS . '/Mindfly/Date/Christian/PassionSedmiza.php';
require_once PATH_LIBS . '/Mindfly/Date/Christian/FortyDayFast.php';

/**
 *
 * возможные правила
 * день недели в
 *
 *
 * 0 - чистые мясоеды
 * 1 - пост по средам и пятницам
 *
 */
class Mindfly_Date_Christian_Remark {
    /**
     * Приорететы
     */
    const DAYS = 50;
    const SEMIDNIZA = 10;
    const FAST = 5;
    const WEEKFAST = 1;
    const MEATFARE = 0;

    private $dataMap = array();
    private $remark;
    private $fortyDayFast;



    public function  __construct($config) {
        $this->config = $config;
        $comments = $this->config->remark;
        $this->remark = array(
            '1' => array(
                '1' => $comments->comment2,   '2' => $comments->comment3,
                '3' => $comments->xerophagy,  '4' => $comments->comment3,
                '5' => $comments->xerophagy,  '6' => $comments->comment3,
                '7' => $comments->comment3),
            '2' => array(
                '1' => $comments->comment2,   '2' => $comments->comment4,
                '3' => $comments->xerophagy,  '4' => $comments->comment4,
                '5' => $comments->xerophagy,  '6' => $comments->comment3,
                '7' => $comments->comment3),
            '3' => array(
                '1' => $comments->xerophagy,  '2' => $comments->comment2,
                '3' => $comments->xerophagy,  '4' => $comments->comment2,
                '5' => $comments->xerophagy,  '6' => $comments->comment5,
                '7' => $comments->comment5),
            '4' => array(
                '1' => $comments->xerophagy,  '2' => $comments->comment2,
                '3' => $comments->xerophagy,  '4' => $comments->comment2,
                '5' => $comments->xerophagy,  '6' => $comments->comment4,
                '7' => $comments->comment4),
            '5' => array(
                '1' => $comments->xerophagy,  '2' => $comments->xerophagy,
                '3' => $comments->xerophagy,  '4' => $comments->comment4,
                '5' => $comments->famine,     '6' => $comments->comment2,
                '7' => $comments->comment1),
            '6' => array(
                '1' => $comments->xerophagy,  '2' => $comments->comment2,
                '3' => $comments->xerophagy,  '4' => $comments->comment2,
                '5' => $comments->xerophagy,  '6' => $comments->comment1,
                '7' => $comments->comment1),
            'assumption_fast' => array(
                '1' => $comments->xerophagy,  '2' => $comments->comment2,
                '3' => $comments->xerophagy,  '4' => $comments->comment2,
                '5' => $comments->xerophagy,  '6' => $comments->comment4,
                '7' => $comments->comment4)
        );

    }
    
    public function getYearMap($year) {
        $this->fortyDayFast = new Mindfly_Date_Christian_FortyDayFast($year);
        $this->processMeatfare($year);        
        $this->processSemidniza($year);
        $this->processDays($year);
        $this->processFast($year);
        $this->processPassionSedmiza($year);
        return $this->dataMap;
    }

    /**
     * Особые дни, накладывающие ограничения в виде поста
     */
    private function processDays($year) {


        $prepareData = array(
            'exaltationOfTheCross' => array(
                'event_id' => $this->config->greatFeasts->exaltationOfTheCross,
                'remark' => $this->config->remark->comment5,
                'day' => Mindfly_Date_Christian_GreatFeasts::exaltationOfTheCross($year),
            ),
            'beheadingOfStJohnTheBaptist' => array(
                'event_id' => $this->config->greatFeasts->beheadingOfStJohnTheBaptist,
                'remark' => $this->config->remark->comment5,
                'day' => Mindfly_Date_Christian_GreatFeasts::beheadingOfStJohnTheBaptist($year),
            ),
            'theEveOfTheophany' => array(
                'event_id' => $this->config->event->other->theEveOfTheophany,
                'remark' => $this->config->remark->sochivo,
                'day' => Mindfly_Date_Christian_Days::theEveOfTheophany($year),
            ),
            'theEveOfEpiphany' => array(
                'event_id' => $this->config->event->other->theEveOfEpiphany,
                'remark' => $this->config->remark->sochivo,
                'day' => Mindfly_Date_Christian_Days::theEveOfEpiphany($year),
            ),
            'lazarusSaturday' => array(
                'event_id' => $this->config->fortyDayFast->lazarusSaturday,
                'remark' => $this->config->remark->lazarus_saturday,
                'day' => $this->fortyDayFast->lazarusSaturday(),
            ),
            'stationsMaryTheEgyptian' => array(
                'event_id' => $this->config->fortyDayFast->stationsMaryTheEgyptian,
                'remark' => $this->config->remark->comment5,
                'day' => $this->fortyDayFast->stationsMaryTheEgyptian(),
            ),

            'presentationOfTheTheotokos' => array(
                'event_id' => $this->config->greatFeasts->presentationOfTheTheotokos,
                'remark' => $this->config->remark->comment3,
                'day' => Mindfly_Date_Christian_GreatFeasts::presentationOfTheTheotokos($year),
            ),
            'theEntryOfOurLordIntoJerusalem' => array(
                'event_id' => $this->config->greatFeasts->theEntryOfOurLordIntoJerusalem,
                'remark' => $this->config->remark->comment3,
                'day' => Mindfly_Date_Christian_GreatFeasts::theEntryOfOurLordIntoJerusalem($year),
            )
        );

        /**
         * Особая логика приходится на Успение в период мясоеда
         * Если праздник  приходится на среду или пятницу, разговляются — рыбой.
         */
        $dayDormitionOfTheTheotokos = Mindfly_Date_Christian_GreatFeasts::dormitionOfTheTheotokos($year);
        $dormitionOfTheTheotokos = new Mindfly_Date($dayDormitionOfTheTheotokos);
        $params = array(
                'dormitionOfTheTheotokos' => array(
                    'event_id' => $this->config->greatFeasts->dormitionOfTheTheotokos,
                    'remark' => $this->config->remark->nofast,
                    'day' => $dayDormitionOfTheTheotokos
        ));
        if (in_array($dormitionOfTheTheotokos->getDateByFormat('N'),array(3,5))) {
            $params['dormitionOfTheTheotokos']['remark'] = $this->config->remark->comment1;
        }
        $prepareData = array_merge($prepareData,$params);


      


        /**
         * особая логика на день апостолов
         * Если праздник святых Петра и Павла не приходится на среду и пятницу,
         * разговляются мясом, в другие дни — рыбой
         */
        $dayPeterAndPavelDay = Mindfly_Date_Christian_GreatFeasts::peterAndPavelDay($year);
        $peterAndPavelDay = new Mindfly_Date($dayPeterAndPavelDay);
        $params = array(
                'peterAndPavelDay' => array(
                    'event_id' => $this->config->greatFeasts->peterAndPavelDay,
                    'remark' => $this->config->remark->nofast,
                    'day' => $peterAndPavelDay->getDay()));
        if (in_array($peterAndPavelDay->getDateByFormat('N'),array(3,5))) {            
            $params['peterAndPavelDay']['remark'] = $this->config->remark->comment1;
        }
        $prepareData = array_merge($prepareData, $params);

        $data = array();
        foreach ($prepareData as $params) {
            $data[] = array(
                'day'      => $params['day'],
                'text'     => $params['remark'],
                'z-index'  => self::DAYS,
                'event_id' => $params['event_id']
            );
        }
       $this->addToDataMap($data);

    }

    /**
     * мясоеды
     * 
     * @param <type> $year
     */
    private function processMeatfare($year) {
        $prepareData = array(
            'autumn' => array(
                'from' => Mindfly_Date_Christian_Fast::assumptionFastEnd($year),
                'to' => Mindfly_Date_Christian_Fast::adventFastBegin($year),
                'weekRemark' => $this->config->remark->xerophagy
            ),
            'winter' => array(
                'from' => Mindfly_Date_Christian_Fast::adventFastEnd($year-1),
                'to' => Mindfly_Date_Christian_Fast::lentFastBegin($year),
                'weekRemark' => $this->config->remark->comment1
            ),
            'spring' => array(
                'from' => Mindfly_Date_Christian_Fast::lentFastEnd($year),
                'to' => Mindfly_Date_Christian_Fast::apostlesFastBegin($year),
                'weekRemark' => $this->config->remark->comment1
            ),
            'summer' => array(
                'from' => Mindfly_Date_Christian_Fast::apostlesFastEnd($year),
                'to' => Mindfly_Date_Christian_Fast::assumptionFastBegin($year),
                'weekRemark' => $this->config->remark->xerophagy
            )
        );

        foreach ($prepareData as $meatfatePeriod => $params) {
             $data = array();
             $time = new Mindfly_Date($params['from']);
             $time->nextDay();
             do {
                 $data[] = array(
                     'day'      => $time->getDay(),
                     'text'     => $this->config->remark->nofast,
                     'z-index'  => self::MEATFARE,
                     'event_id' => $this->config->meatfare->$meatfatePeriod
                 );
                 $time->nextDay();
             } while ($time->getPrevDay()->getDay() != $params['to']);
             $this->addToDataMap($data);             
             // по полученным данным проходим и расставляем посты в среду
             $weekFast = $this->processWeekFasts($data, $params['weekRemark']);             
             $this->addToDataMap($weekFast);
        }
    }

    /**
     * Добавляем посты семиндицы
     */
    private function processSemidniza($year) {        
        $dataSemidniza = array(
            'christmasWeek' => array(
                'text' => $this->config->remark->nofast,
                'days' => Mindfly_Date_Christian_Semidniza::christmasWeek($year),
            ),
            'taxCollectorAndPhariseeWeek' => array(
                'text' => $this->config->remark->nofast,
                'days' => Mindfly_Date_Christian_Semidniza::taxCollectorAndPhariseeWeek($year),
            ),
            'carnivalWeek' => array(
                'text' => $this->config->remark->comment3,
                'days' => Mindfly_Date_Christian_Semidniza::carnivalWeek($year),
            ),
            
            'eastertideWeek' => array(
                'text' => $this->config->remark->nofast,
                'days' => Mindfly_Date_Christian_Semidniza::eastertideWeek($year),
            ),
            'whitsunWeek' => array(
                'text' => $this->config->remark->nofast,
                'days' => Mindfly_Date_Christian_Semidniza::whitsunWeek($year),
            ),

        );
        foreach ($dataSemidniza as $week => $params) {
            $dataParam = array(
                'text'     => $params['text'],
                'z-index'  => self::SEMIDNIZA,
                'event_id' => $this->config->semidniza->$week
            );
            foreach ($params['days'] as $day) {
                $dataParam['day'] = $day;
                $data[] = $dataParam;
            }            
        }

        // sexagesima - особая логика
        $days = Mindfly_Date_Christian_Semidniza::sexagesima($year);
        $dataParam = array(                
                'z-index'  => self::SEMIDNIZA,
                'event_id' => $this->config->semidniza->sexagesima
         );
        for ($i = 1; $i<=7; $i++) {
            $dataParam['text'] = in_array($i, array(3,5)) ?
                $this->config->remark->comment1 : $this->config->remark->nofast;            
            $dataParam['day'] = $days[$i-1];
            $data[] = $dataParam;
        }

        $this->addToDataMap($data);
    }

    /**
     * пройтись по мясоеду, добавить недельного поста.
     */
    private function processWeekFasts($meatfare, $remark) {
        
        $remarkData = array(
            'text'     => $remark,
            'z-index'  => self::WEEKFAST,
            'event_id' => $this->config->event->fastweek
        );
        $data = array();
        foreach ($meatfare as $dayParams) {
            $day = new Mindfly_Date($dayParams['day']);            
            if (in_array($day->getDateByFormat('N'), array(3,5))) {
                $remarkData['day'] = $day->getDay();
                $data[] = $remarkData;
            }
        }
        
        return $data;
    }

    private function processFast($year) {
        $data = array();
        $fastTable = new Mindfly_Date_Christian_Fast($this->config);
        $fastDays = $fastTable->advent($year);
        $eventId = $this->config->event->fast->advent;

        foreach ($fastDays as $day) {
            $time = new Mindfly_Date($day);            
            if ($time->getDay() < $year . '-12-20') {
                $remark =  $this->remark['1'][$time->getDateByFormat('N')];
            } elseif ($time->getDay() < ($year+1) . '-01-02') {
                $remark =  $this->remark['2'][$time->getDateByFormat('N')];
            } elseif ($time->getDay() < ($year+1) . '-01-07') {
                $remark =  $this->remark['3'][$time->getDateByFormat('N')];
            } 
            $data[] =  array(
                'text'     => $remark,
                'z-index'  => self::FAST,
                'event_id' => $eventId,
                'day' => $day
            );
        }
        $this->addToDataMap($data);


        $data = array();
        $fastDays = $fastTable->assumption($year);
        $assumptionId = $this->config->event->fast->assumption;
        foreach ($fastDays as $day) {
            $time = new Mindfly_Date($day);
            $remark = $this->remark['assumption_fast'][$time->getDateByFormat('N')];
            $data[] =  array(
                'text'     => $remark,
                'z-index'  => self::FAST,
                'event_id' => $assumptionId,
                'day' => $day
            );
        }
        $this->addToDataMap($data);


        $data = array();
        $fastDays = $fastTable->apostels($year);        
        foreach ($fastDays as $day) {
            $time = new Mindfly_Date($day);
            $remark = $this->remark['6'][$time->getDateByFormat('N')];
            $data[] =  array(
                'text'     => $remark,
                'z-index'  => self::FAST,
                'event_id' => $this->config->event->fast->apostels,
                'day' => $day
            );
        }
        $this->addToDataMap($data);


        //великий пост
        $data = array();
        $fastDays = $fastTable->lent($year);
        $week = 1;
        $i = 0;
        foreach ($fastDays as $day) {
            if ($i == 7) {
                $i = 1;
                $week++;
            } else {
                $i++;
            }
            $remark = ($week == 1)
                     ? $this->config->remark->xerophagy
                     : $this->remark['4'][$i];
                          
            $data[] =  array(
                'text'     => $remark,
                'z-index'  => self::FAST,
                'event_id' => $this->config->event->fast->lent,
                'day' => $day
            );            
        }
        $this->addToDataMap($data);
        

    }



    /**
     * Страстная седмица
     */
    private function processPassionSedmiza($year) {        
        $data =  array(
            array(
                'text'     => $this->remark['5'][1],
                'z-index'  => self::SEMIDNIZA,
                'event_id' => $this->config->passion->monday,
                'day' => Mindfly_Date_Christian_PassionSedmiza::monday($year)
            ), array(
                'text'     => $this->remark['5'][2],
                'z-index'  => self::SEMIDNIZA,
                'event_id' => $this->config->passion->tuesday,
                'day' => Mindfly_Date_Christian_PassionSedmiza::tuesday($year)
            ), array(
                'text'     => $this->remark['5'][3],
                'z-index'  => self::SEMIDNIZA,
                'event_id' => $this->config->passion->wednesday,
                'day' => Mindfly_Date_Christian_PassionSedmiza::wednesday($year)
            ), array(
                'text'     => $this->remark['5'][4],
                'z-index'  => self::SEMIDNIZA,
                'event_id' => $this->config->passion->thursday,
                'day' => Mindfly_Date_Christian_PassionSedmiza::thursday($year)
            ), array(
                'text'     => $this->remark['5'][5],
                'z-index'  => self::SEMIDNIZA,
                'event_id' => $this->config->passion->friday,
                'day' => Mindfly_Date_Christian_PassionSedmiza::friday($year)
            ), array(
                'text'     => $this->remark['5'][6],
                'z-index'  => self::SEMIDNIZA,
                'event_id' => $this->config->passion->saturday,
                'day' => Mindfly_Date_Christian_PassionSedmiza::saturday($year)
            ),
        );
        $this->addToDataMap($data);

    }



    private function addToDataMap($data) {
        $this->dataMap = array_merge($this->dataMap, $data);
    }
}