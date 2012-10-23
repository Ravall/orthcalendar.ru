<?php
require_once 'MfSystemObjectTextTable.php';

/**
 * Description of MfSystemObjectRow
 *
 * @author user
 */
class MfSystemObjectRow extends Zend_Db_Table_Row_Abstract {

    /**
     * для общего случая возвращаем объект
     * 
     * @return <type>
     */
    public function getObject() {
        return $this;
    }

    /**
     * сохраняем запись
     */
    public function  save() {
        $this->updated = new Zend_Db_Expr('NOW()');
        /**
         * если был модифицирован parent_id 
         * то пересчитываем parent_ids
         */
        if (key_exists('parent_id', $this->_modifiedFields)) {
            $this->refreshParentIds();
        }       
        parent::save();        
    }

    /**
     * активируем объект
     */
    public function setActive() {
        $this->_setStatus(STATUS_ACTIVE);
    }

    /*
     * ставим на паузу
     */
    public function setPause() {
        $this->_setStatus(STATUS_PAUSE);
    }

    /*
     * удаляем
     */
    public function setDelete() {
        $this->_setStatus(STATUS_DELETED);
    }

    public function setParentId($id) {
        $this->parent_id = $id;
        $this->save();
    }

    /**
     * получаем все дочерние объекты
     * @todo возможно функция не нужна
     * 
     * @param <type> $category
     * @return array
     */
    public function getChildrens() {        
        $childrens = array();
        MfSystemObjectTable::getObjectsByParentId($this->id, $this->getTable()->fetchAll(), $childrens);
        return $childrens;
    }



    /**
     * добавляем объект. Связь id <-> parent_id
     *
     * @param MfSystemObject $object
     */
    public function addSubObject($object) {
        $object->parent_id = $this->id;
        $object->save();
        return $this;
    }

    /**
     * Сохраняем изображение
     *
     * @param <type> $imageName
     */
    public function setImage($imageName) {
        // удаляем текущее изображение, если таковое имеется
        if ($this->image) {
            unlink(PATH_BASE . IMAGE_RAW_PATH . $this->image);
        }
        $this->image = $imageName;
        $this->save();
    }

    public function getImage($width ,$height) {
        if (!($this->image &&
            file_exists(PATH_BASE . IMAGE_RAW_PATH . $this->image))) {
            return false;
        }
        if (!file_exists(
                $path = PATH_BASE . IMAGE_OBJECT_CALENDAR_PATH .
                $width . 'x' . $height . '/' . $this->image)
             && file_exists(PATH_BASE . IMAGE_RAW_PATH . $this->image))
        {
                // если файла не существует, то сделаем
                $image = new Imagick();
                $link = PATH_BASE . IMAGE_RAW_PATH . $this->image;
                $image->readImage($link);
                $image->thumbnailImage($width, 0);
                $image->cropImage($width, $height, 0, 0);
                $image->writeImage($path);
        }
        return IMAGE_OBJECT_PUBLIC_CALENDAR_PATH . $width.'x'.$height.'/'.$this->image;


        

        
    }

    /**
     * устанавливает текст для объекта
     */
    public function setText($params, $status = 'acitve') {
        if ($this->isTextExist($status)) {
            // если текст существует - то обновляем его
            $this->getText($status)->updateText($params);
        } else {
            //если текста для этого языка не найдено -
            //то добавляем 
            $this->addText($params, $status);
        }
    }



   /**
     * Получаем текст
     **/
    public function getText($status = 'active') {        
        if ($this->isTextExist($status)) {
           return $this->getSystemText($status);
        } else {
            //если текста для этого языка не найдено -
            //то добавляем его c пустым содержанием
            $this->addText(array(), $status);
            return $this->getSystemText($status);
        }
    }


    /**
     * добвляет текст к объекту,
     *
     *
     * @param <type> $params
     * @param <type> $status
     * @return MfSystemObjectRow
     */
    private function addText($params, $status = 'active') {
        // проверяем инициализирован ли объект
        if (!$this->id) {
            throw new Exception('objectId not equal 0');
        }
        $texts = new MfSystemTextTable();
        $text = $texts->createRow();
        $text->title = isset($params['title']) ? $params['title']:'';
        $text->annonce = isset($params['annonce']) ? $params['annonce'] : '';
        $text->content = isset($params['content']) ? $params['content'] : '';
        $text->save();

        $this->joinText($text, $status);

        return $this;
    }



    /**
     * сохраняет в поле parent_ids
     * идентификаторы родителей от текущего к первоначальному
     */
    private function refreshParentIds() {        
        $object = new MfSystemObjectTable();
        $parent = clone $this;
        $arrayOfParentIds = array();
        while ($parent->parent_id) {
            $parent = $object->fetchRow('id = ' . $parent->parent_id);
            $arrayOfParentIds[] = $parent->id;
        }
        $this->parent_ids = (implode(',', $arrayOfParentIds));
        return $this;
    }


    /**
     * общая функция изменения статуса
     *
     * @param <type> $status
     */
    private function _setStatus($status) {
        $this->status = $status;
        $this->save();
        // меняем статус каскадно
        foreach ($this->getChildrens() as $child) {
            $child->status = $status;
            $child->save();
        }
    }


    /**
      * Связываем объект с текстом
      **/
    private function joinText($text, $status = 'acitve') {
        $objectTexts = new MfSystemObjectTextTable();
        $objectTextRow = $objectTexts->createRow();
        $objectTextRow->status = $status;
        $objectTextRow->system_object_id = $this->id;
        $objectTextRow->system_text_id = $text->id;
        $objectTextRow->save();
        return $this;
    }


    /**
     * получаем системный текст
     *
     * @return <type>
     */
    private function getSystemText($status) {
        
        // находим связь
        $textObjects = new MfSystemObjectTextTable();
        $textObjectRowset = $textObjects->fetchAll(array(
            'system_object_id = ?' => $this->id,
            'status = ?' => $status
        ));
        $textObject = $textObjectRowset->current();
        return $textObject->findParentRow('MfSystemTextTable');
    }



    /**
     * проверяем существует ли текст для этого объекта
     **/
    private function isTextExist($status = 'active') {
        $objectTexts = new MfSystemObjectTextTable();
        $select = $objectTexts->select()->where('status = ?', $status);        
        $x = $this->findDependentRowset('MfSystemObjectTextTable','Object', $select);        
        return (bool) count($x);
    }






}
?>
