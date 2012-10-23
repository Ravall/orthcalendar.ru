<?php
require_once SANCTA_PATH . '/Abstract/Cache.php';
class Sancta_Abstract_Peer extends Sancta_Abstract_Cache {

    private function  __construct() {}

    /**
     * Создание нового объекта путем делегирования
     * задачи в конструктор модели
     */
    public static function create($params = array()) {
        /**
         * очищаем кеш, удаляем все списки объектов, помеченных тегом $tag 
         */
        self::clearCachedListOfObject(array(static::$tag));
        $className = static::$className;    
        return new $className($params);
    }

    /**
     * получение существующего объекта статическим методом,
     * путем делегирование задачи в конструктор модели
     * 
     * @param <type> $id
     * @return className
     */
    public static function getById($id) {
        $className = static::$className;
        return new $className((int) $id);
    }
    
    public static function getMapperTable() {
        $mapperTable = static::$mapperTable;
        return new $mapperTable();
    }
    
    public static function getClassListName() {
        return static::$classListName;
    }
   
    /**
     * Получить список всех моделей по статусам
     *
     * область видимости protected,
     * для того что бы его перехватил кеширующий метод
     * @return type 
     */
    protected static function getAll() {                     
        $modelTable = self::getMapperTable();
        $idRows = $modelTable->getAll(); 
        $classListName = self::getClassListName();
        $list = new $classListName($idRows, 'id');
        return $list;
    }
   
    
   
}