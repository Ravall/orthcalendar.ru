<?php
require_once dirname(__FILE__) . '/Abstract/ImagedObject.php';
require_once PATH_BASE . '/models/Db/Mapper/SystemObject.php';
require_once SANCTA_PATH . '/Bundle/ReestrObjectParam.php';

abstract class Sancta_Object extends Sancta_Abstract_ImagedObject {

    private $id;
    private $parent_id;
    private $user_id;
    private $status;
    private $created;
    private $updated;
    private $created_class;
    private $parent_ids;
    private $url;




    /**
     * массив ключей, который можно обновить через update
     * у "общего объекта" модели
     *
     * @var type
     */
    private $objectParamKeys = array('status');

    protected function _getById($id) {
        parent::_getById($id);
        $mapperSystemObject = new Db_Mapper_SystemObject();
        $object = $mapperSystemObject->getDbTable()->find($id)->current();
        $this->_setObject($object);
    }

    public function getUrl()
    {
        return $this->url;
    }
    /**
     * получить идентификатор объекта
     * @return <type>
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Получает статус объекта
     * @return <type>
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Делает объект активным
     */
    public function setActived() {
        $this->update(array(
            'status' => STATUS_ACTIVE
        ));
    }

    /**
     * Ставит объект на паузу
     */
    public function setPaused() {
        $this->update(array(
            'status' => STATUS_PAUSE
        ));
    }

    /**
     * Помечает объект удаленным
     */
    public function setDeleted() {
        $this->update(array(
            'status' => STATUS_DELETED
        ));
    }

    /**
     * создать объект
     *
     * @param <type> $className
     * @param <type> $params
     */
    protected function createObject($className, $params) {
        $mapperSystemObject = new Db_Mapper_SystemObject();
        $objectParams = array('created_class' => $className);
        if (isset($params['parent_id'])) {
            $objectParams = array_merge($objectParams, array('parent_id' => $params['parent_id']));
        }
        $object = $mapperSystemObject->create($objectParams);
        $this->_setObject($object);
        parent::_create($params);
    }

    protected function _setObject($object) {
        $this->id = $object->id;
        $this->parent_id = $object->parent_id;
        $this->user_id = $object->user_id;
        $this->status = $object->status;
        $this->created = $object->created;
        $this->updated = $object->updated;
        $this->created_class = $object->created_class;
        $this->parent_ids = $object->parent_ids;
        $this->url = $object->url;
        parent::_setObject($object);
    }

    /**
     * получаем все привязанные объекты к текущему
     * @return <type>
     */
    public function getRelatedObjets() {
         $systemRelationTable = new Db_Mapper_SystemRelation();
         $ids = $systemRelationTable->findById($this->getId());
         $idArray = array();
         foreach ($ids as $id) {
             $idArray[] = $id['parent_object_id'];
         }
         return $idArray;
    }

    /**
     * привязан ли объект к $objectId
     * @param <type> $objectId
     */
    public function isRelateTo($objectId) {
        return in_array($objectId, array_values($this->getRelatedObjets()));
    }

    public function getCategoryId() {
        return $this->parent_id;
    }

    /**
     * обновление "общего объекта" модели
     *
     * @param type $param
     */
    public function update($param) {
        $table = new Db_Mapper_SystemObject();
        $model = $table->update($this->getId(), $param, $this->objectParamKeys);
        $this->_setObject($model);
        parent::update($param);

    }


    public function setReestrParam($key, $value) {
        $params = new Sancta_Bundle_ReestrObjectParam($this->getId());
        return $params->setParam($key, $value);
    }

    public function getReestrParam($key) {
        $params = new Sancta_Bundle_ReestrObjectParam($this->getId());
        return $params->getParam($key);
    }

    public function getReestrParams() {
        $params = new Sancta_Bundle_ReestrObjectParam($this->getId());
        return $params->getParams();
    }



}