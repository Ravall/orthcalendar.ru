<?php
require SANCTA_PATH . '/Object.php';
/**
 * @todo убрать потом наследование
 */
abstract class Sancta_Abstract_System extends Sancta_Object {
    
     /**
     * параметры которые нужно обновлять через update
     * @var type 
     */
    protected $modelParamKeys = array();
    
    /**
     * опции кеширования
     *  Sancta_Abstract_Cache::UPDATE_TAG_INDEX - индексы которые удаляются при обновлении
     * @var type 
     */
    protected $cacheOptions = array(
        Sancta_Abstract_Cache::UPDATE_TAG_INDEX => array(),
    );
    
    /**
     * Общий конструктор
     * Создает объект или получает по id
     *
     * @param type $params
     */
    public function  __construct($params = array()) {
        parent::__construct();        
        if (isset ($params)) {
            if (is_array($params)) {
                $this->_createModel($params);
            } else {
                $id = (int) $params;
                $this->_getById($id);
            }
        } else {
            throw new Exception('params mat be not null');
        }
    }

    /**
     * обновление данных модели
     * 
     * @param array $param 
     */
    public function update(array $param = array()) {        
        $this->cacheCleanForUpdate();        
        $mapperTable = $this->mapperTable;
        $table = new $mapperTable();
        $model = $table->update($this->getId(), $param, $this->modelParamKeys);
        $this->_setModel($model);
        parent::update($param);
    }
    

    /**
     * получаем модель по id
     *
     * @param <type> $id
     */
    protected function _getById($id) {
        parent::_getById($id);
        $mapperTable = $this->mapperTable;
        $table = new $mapperTable();
        $model = $table->getById($id);
        $this->_setModel($model);
    }


    /**
     * создаем статью.
     * Основные параметры можно поместить в конструктор
     * @param <type> $params
     */
    protected function _createModel($params = array()) {                
        $params = $this->mergeParams($params);        
        $this->createModelValidate($params);        
        $this->beginTransaction();
        try {
            $this->createObject(__CLASS__, $params);            
            $maperTable = $this->mapperTable;
            $table = new $maperTable();
            $model = $table->create(
                array_merge(
                    array('object_id' => $this->getId()),
                    $params
                )
            );
            $this->_setModel($model);
            $this->commitTransaction();
         } catch (Exception $e) {
           $this->rollBackTransaction();
           throw $e;
         }
    }

    /**
     * иногда нужно, при создании объекта
     * добавить что-то в инициализиующие параметры.
     * Для этого перегружается эта функция
     * 
     * @param <type> $params
     * @return <type>
     */
    protected function mergeParams($params) {
        return $params;
    }

    /**
     * Валидация параметров модели при создании
     *
     * @return <type>
     */
    protected function createModelValidate($params) {
        return TRUE;
    }
    
    /**
     * очищаем кеш при обновлении записи
     */
    private function cacheCleanForUpdate() {
        if (!isset ($this->cacheOptions[Sancta_Abstract_System::UPDATE_TAG_INDEX])) {
            return FALSE;
        }
        $tags = $this->cacheOptions[Sancta_Abstract_System::UPDATE_TAG_INDEX];
        if (empty($tags)) {
            return;
        }
        parent::clearCachedListOfObject($tags);
    }
    
    public function getMapperTable() {
        return $this->mapperTable;
    }
}
?>