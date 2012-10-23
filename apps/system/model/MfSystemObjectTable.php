<?php
require_once dirname(__FILE__) . '/MfSystemObjectRow.php';
/* 
 * Модель для работы с таблицей mfSystemObject
 */
class MfSystemObjectTable extends Zend_Db_Table_Abstract {

    protected $_name = 'mf_system_object';
    protected $_primary = 'id';
    // класс работы с одной строкой
    protected $_rowClass = 'MfSystemObjectRow';        
    // зависимые таблицы
    protected $_dependentTables = array(
        'MfSystemObjectTextTable',        
        'MfCalendarCategoryTable',
        'MfCalendarEventTable',
    );

    /**
     * регистрация объекта
     * 
     * @param <type> $params
     */
    public static function register($params = array()) {
        // обязательным должен быть параметр created_class
        if (!$params['created_class']) {
            throw new Exception('created_class is null');
        }
        $params = array_merge(
              $params,
              array('created' => new Zend_Db_Expr('NOW()'))
        );
        $object = new MfSystemObjectTable();
        $row = $object->createRow($params);
        $row->save();
        return $row;
    }

    /**
     * получение всех дочерних елементов из заданного множества
     * 
     * @param <type> $id
     * @param <type> $objects
     * @param <type> $result
     */
    public static function getObjectsByParentId($id, $objects, &$result) {
        foreach ($objects as $object) {
            if ($object->parent_id == $id) {
                $result[] = $object;                
                self::getObjectsByParentId($object->id, clone $objects, $result);
            }

        }
    }


    /**
     * превращение массива в дерево
     *
     * @param <type> $categoryes
     * @return array
     */
    public static  function getTreeArray($objects) {
        if (count($objects) == 0) {
            return array();
        }
        // приведем к нормальному виду
        foreach ($objects as $object) {            
            $count = $object->getObject()->parent_ids?
                     count(explode(',', $object->getObject()->parent_ids)):0;
            $treeArray[$count][] = $object;
        }

        // сортируем по глубине
        ksort($treeArray);
        $totalTreeArray = array();
        foreach ($treeArray as $level => $levelObjects) {
            foreach ($levelObjects as $object) {
                $path = $object->getObject()->parent_ids
                      ? explode(',', $object->getObject()->parent_ids)
                      : 0;
                self::insertIntoArray($path, $totalTreeArray, $object);
            }
        }
        return $totalTreeArray;
    }

    /**
     * елемент древовидного массива
     *
     * @param <type> $value
     * @return <type>
     */
    private static function insertValue($value) {
        return array(
            'object' => $value,
            'sub' => array(),
            '_object' => $value->getObject()
        );
    }

    /**
     * вставка элемента в дерево
     *
     * @param <type> $path
     * @param <type> $array
     * @param <type> $value
     */
    private static function insertIntoArray($path, &$array, $value) {
        if (!$path) {
            $array[$value->id] = self::insertValue($value);
        } else {
            $x = $array;
            $realPath = '';
            foreach (array_reverse($path) as $id) {
                if (isset($x[$id])) {
                    $x = $x[$id]['sub'];
                    $realPath.= '[' . $id . ']["sub"]';
                }
             }
             eval('$array' . $realPath . '[$value->id] = self::insertValue($value);');
        }    
   }


    
   



}

?>