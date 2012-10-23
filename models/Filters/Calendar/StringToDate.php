<?php
/**
 * преобразует дату вида
 * "2 октября 2010" в "2010-10-02"
 */
class Filter_Calendar_StringToDate implements Zend_Filter_Interface
{
    private $search = array(
        'января', 'февраля', 'марта', 'апреля', 'мая', 'июня',
        'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря',
        ' '
    );
    private $replace = array(
        '-01-', '-02-', '-03-', '-04-', '-05-', '-06-',
        '-07-', '-08-', '-09-', '-10-', '-11-', '-12-',
        ''
    );
    public function filter($value) {
        $value = str_replace($this->search, $this->replace, $value);
        $value = date('Y-m-d',strtotime($value));
        return ($value == '1970-01-01') ? false : $value;
   }

   /**
    * переводим обратно
    *
    * @param <type> $value
    */
   public function back($value) {
      $date = date('j+-m-+Y',strtotime(trim($value)));
      if ($date == '1+-01-+1970') {
          return '';
      }

      $value = str_replace($this->replace, $this->search, $date);
      $value = str_replace('+', ' ', $value);

      return $value;
   }
 }