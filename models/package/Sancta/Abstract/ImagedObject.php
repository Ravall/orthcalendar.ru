<?php
require_once SANCTA_PATH . '/Text.php';
/**
 * Слой абстракции для связи объекта sancta 
 * c методами для работы с изображениями
 */
abstract class Sancta_Abstract_ImagedObject extends Sancta_Text {
    private $image;

    protected function _setObject($object) {
        $this->image = $object->image;
    }

    /**
     * получаем приявзанное к объекту изображение
     * 
     * @param <type> $width
     * @param <type> $height
     * @return <type>
     */
    public function getImage($width, $height) {
        
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
     * Сохраняем изображение
     *
     * @param <type> $imageName
     */
    public function setImage($imageName) {
        // удаляем текущее изображение, если таковое имеется
        if ($this->image) {
            @unlink(PATH_BASE . IMAGE_RAW_PATH . $this->image);
        }
        $this->image = $imageName;
        $mapperSystemObject = new Db_Mapper_SystemObject();
        $object = $mapperSystemObject->setImage($this->getId(), $imageName);        
    }

    public function deleteImage() {
        // удаляем текущее изображение, если таковое имеется
        if ($this->image) {
            @unlink(PATH_BASE . IMAGE_RAW_PATH . $this->image);
        }
        $this->image = null;
        $mapperSystemObject = new Db_Mapper_SystemObject();
        $object = $mapperSystemObject->setImage($this->getId(), null);
    }
}