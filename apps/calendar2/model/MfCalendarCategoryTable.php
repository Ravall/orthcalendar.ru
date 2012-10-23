<?php
require_once SYSTEM_PATH . '/model/SystemObjectLayerTable.php';
require_once PATH_LIBS . '/Mindfly/Validate/Calendar/Category.php';

/* 
 *   Модель Категории календаря.
 *   Содержит методы управления категориями событий
 */
class MfCalendarCategoryTable extends SystemObjectLayerTable {

    protected $_name = 'mf_calendar_category';
    protected $_primary = 'id';
    // класс работы с одной строкой
    protected $_rowClass = 'MfCalendarCategoryRow';    
    /**
     * правила зависимых таблиц
     */
    protected $_referenceMap    = array(
        'Object' => array (
            'columns' => array('id'),
            'refTableClass' => 'MfSystemObjectTable',
            'refColumns' => array('id')
        )
    );
   

    /**
     * создаем категорию
     * и связываем ее с объектом, добавляем заголовок
     * 
     * @param <type> $title
     * @param <type> $status
     * @return self
     */
    public function create($title, $status = 'active') {        
        $valid = new Mindfly_Validate_Calendar_Category();
        if (!$valid->isValid($title)) {
            throw new Exception('Не удалось добавить категорию');
        }
        $object = MfSystemObjectTable::register(
            array('created_class' => $this->_name)
        );
        
        $object->setText(array('title' => $title), $status);
        $categoryClass = new MfCalendarCategoryTable();
        $category = $categoryClass->createRow();
        $category->id = $object->id;
        $category->save();        
        return $category;
    }


    /**
     * проверка на существование категории
     *
     * @param string $categoryName
     * @return bool
     */
    public function isExist($categoryName, $categoryId = null) {
        $select = $this->getAdapter()->select()->from(array('cc' => 'mf_calendar_category'))                
                ->join(array('so' => 'mf_system_object'), 'so.id = cc.id')
                ->where('so.status != "' . STATUS_DELETED . '"')
                ->where('so.created_class = "mf_calendar_category"')
                ->join(array('sot' => 'mf_system_object_text'), 'sot.system_object_id = so.id')
                ->join(array('st' => 'mf_system_text'),'sot.system_text_id = st.id')
                ->where('st.title = '.$this->getAdapter()->quote($categoryName));        
        if ($categoryId) {
            $select->where('cc.id <> '.$categoryId);
        }        
        return (bool) $select->query()->fetchAll();
    }

    

}
?>