<?php
class Zend_View_Helper_CalendarNavigation extends Zend_View_Helper_Abstract {
    public $view;
    private $month;
    private $monthSort;

    public function  __construct() {
        $this->month = Mindfly_Date::getFullMonthNamesArray();
        $this->monthSort = Mindfly_Date::getShortMonthNamesArray();
    }
   

    public function calendarNavigation ($data) {
        $mindflyData = new Mindfly_Date($data);
        $output = '<div class="calendar_navigation">';
        $output .= '<div class="span-8"> &nbsp; </div>';        
        $output .= '<div class="current span-7"> ' . $this->view->monthCalendar($mindflyData->getY(),$mindflyData->getM()) . ' </div>';        
        $output .= '<div class="span-8 last"> &nbsp; </div>';
        $output .= '</div>';
        $output .= $this->calendarNavigationLine($mindflyData->getY(), $mindflyData->getM());
        return $output;
    }

    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }


    public function calendarNavigationLine($year, $month) {
        $output = '<div class="span-24 last calendar_line">';
        $output .= '<span><a href="'
                . $this->view->url(array('date' => $year-1 . '-01'), 'orthodoxy')
                . '"> &larr; '.($year-1).'</a></span>';
        for ($i=1; $i<=12; $i++) {
                $current = ($i == $month) ? 'current_month' : '';
                $output.='<span><a href="' 
                       . $this->view->url(array('date' =>
                           $year . '-' . sprintf('%02s', $i)), 'orthodoxy')
                       . '" class=' . $current . '>' . $this->monthSort[$i] . '</a></span>';
        }
        $output .= '<span><a href="'
                . $this->view->url(array('date' => $year+1 . '-01'), 'orthodoxy')
                . '">' . ($year+1) . '&rarr; </a></span>';
        $output .= '<br/><a href="'
                . $this->view->url(array('date' => date('Y-m',time())), 'orthodoxy')
                . '">  сегодня </a>';

        $output .= '</div>';
        return $output;
    }

    

   
}