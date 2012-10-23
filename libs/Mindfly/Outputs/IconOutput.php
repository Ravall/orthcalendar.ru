<?php
/* 
 * Хелпер для вывода иконок
 *
 */
class Mindfly_Outputs_IconOutput extends Zend_View_Helper_Abstract {
    const ICON_CREATE = 'create';
    private $map;

    public function  __construct() {
        $this->map['create'] = 'plus-white.png';
        $this->map['edit'] = 'pencil.png';
        $this->map['delete'] = 'cross.png';
        $this->map['ok'] = 'tick.png';
        $this->map['calendar-add'] = 'calendar--plus.png';
        $this->map['calendar'] = 'calendar-day.png';
        $this->map['clock'] = 'clock.png';
    }

    public function iconOutput($icon) {
        return '<img  src="/images/icons/' . $this->map[$icon] . '" border=0>';
    }
}

?>
