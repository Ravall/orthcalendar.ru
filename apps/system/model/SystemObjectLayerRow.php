<?php
/* 
 * Абстрактный слой, наследующий от себя все объектные классы
 * Здесь не должно быть логики. Только вызов методов объекта
 */
abstract class SystemObjectLayerRow extends Zend_Db_Table_Row_Abstract {
    /**
     * получаем связанный объект
     * при этом в $_referenceMap Table должна быть указана связь
     * с таблицей Object
     * 
     * @return <type>
     */
    public function getObject() {
        return $this->findParentRow('MfSystemObjectTable');
    }

    /**
     * получаем связанный текст
     *
     * @return <type>
     */
    public function getText() {
        return $this->getObject()->getText();
    }

    /**
     * сохраняем текст
     * 
     * @param <type> $params
     * @return <type>
     */
    public function setText($params) {
        return $this->getObject()->setText($params);
    }

    /**
     * Устанавливаем статус "удален"
     * 
     * @return <type>
     */
    public function setDelete() {
        return $this->getObject()->setDelete();
    }

    /**
     * Устанавливаем статус "активен"
     * 
     * @return <type>
     */
    public function setActive() {
        return $this->getObject()->setActive();
    }

    /**
     * обновление объекта
     * @param <type> $params
     */
    public function update($params) {        
        foreach ($params as $key => $value) {
            $method = 'set' . ucfirst($key);            
            if (method_exists($this, $method)) {                
                $this->$method($value);
            } else {
                $this->$key = $value;
            }
        }        
        $this->save();
    }
}
?>
