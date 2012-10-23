<?php
interface Sancta_Interface_Article {
    /**
     * создать статью
     */
    public static  function create($params);
    /**
     * получить статью по ее идентификатору
     */
    public static function getById($id);
    /**
     * получить стати по идентификатору объекта к которому они привязаны
     */
    public static function getByEventId($objectId);    
    /**
     *  получить все статьи в зависимости от статуса
     */
    public static function getAll($statuses);
    /**
     * получить заглавие статьи
     */
    public function getTitle();
    /**
     * Получить содержание статьи
     */
    public function getContent();
    /**
     * установить новый заголовок статьи
     */
    public function setTitle($text);
    /**
     * установить новое содержание статьи
     */
    public function setContent($text);
    /**
     * связать статью с событием
     */
    public function relateToEvent($objectId);

}