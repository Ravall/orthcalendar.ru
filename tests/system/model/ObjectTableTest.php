<?php
require_once realpath(dirname(__FILE__).'/../../../') . '/config/init.php';
require_once PATH_TESTS . '/init.php';
require_once PATH_LIBS_ZEND . '/Zend/View/Helper/Abstract.php';
require_once PATH_LIBS . '/Mindfly/Outputs/TreeMenuOutput.php';

require_once PATH_LIBS_ZEND. '/Zend/Db/Table/Abstract.php';
require_once SYSTEM_PATH.'/model/MfSystemObjectTable.php';

/**
 * Description of objectTableTest
 *
 * @author user
 */
class objectTableTest extends Tests_Lib_TestCaseSimple {

    /**
     * проверяем правильность подстановки
     * данных при регистрации объекта
     * проверяемая функция MfSystemObjectTable::register
     **/
    public function testSuccessInsert() {
        $params = array('created_class' => __CLASS__);
        $object = MfSystemObjectTable::register($params);        
        $this->assertGreaterThan(0, $object->id);        
        $this->assertDataTime($object->created, date('Y-m-d H:i:s',time()), 2, 'time incorrect');
        $params = array_merge($params, array(
                 'parent_id' => $object->id,
                 'status'      => STATUS_PAUSE,
                 'user_id'     => TestTools::getTestUserId(),
        ));
        $newObjectExcept = MfSystemObjectTable::register($params);        
        $this->assertEquals($newObjectExcept->parent_id, $object->id);
        $this->assertEquals($newObjectExcept->status, STATUS_PAUSE);
        $this->assertEquals($newObjectExcept->user_id, TestTools::getTestUserId());
        $this->assertDataTime($newObjectExcept->created, date('Y-m-d H:i:s',time()), 2, 'time incorrect');      
    }

    /**
     * провайдер поступающих значений при
     * которых дожно бросится исключение
     **/
    public function providerRegisterException() {
        return array(
            array(),
        );
    }

    /**
     * ожидаем бросок исключения в случае неверных параметров
     * @expectedException Exception
     * @dataProvider providerRegisterException
     */
    public function testRegisterException($params) {
        MfSystemObjectTable::register($params);
    }
    
    public function  testGetObjectsByParentId() {

    }

    /**
     * тестируем функцию получения массива объектов древовидного
     */
    public function testGetTreeArray() {
        $object5_0 = MfSystemObjectTable::register(array('created_class' => __CLASS__));
        $object6_5 = MfSystemObjectTable::register(array('created_class' => __CLASS__));
        $object11_5 = MfSystemObjectTable::register(array('created_class' => __CLASS__));
        $object7_0 = MfSystemObjectTable::register(array('created_class' => __CLASS__));
        $object13_0 = MfSystemObjectTable::register(array('created_class' => __CLASS__));
        $object3_13 = MfSystemObjectTable::register(array('created_class' => __CLASS__));
        $object9_3 = MfSystemObjectTable::register(array('created_class' => __CLASS__));
        $object4_13 = MfSystemObjectTable::register(array('created_class' => __CLASS__));
        $object10_4 = MfSystemObjectTable::register(array('created_class' => __CLASS__));
        $object8_0 = MfSystemObjectTable::register(array('created_class' => __CLASS__));
        $object1_8 = MfSystemObjectTable::register(array('created_class' => __CLASS__));
        $object2_12 = MfSystemObjectTable::register(array('created_class' => __CLASS__));
        $object15_12 = MfSystemObjectTable::register(array('created_class' => __CLASS__));
        $object12_8 = MfSystemObjectTable::register(array('created_class' => __CLASS__));
        $object14_8 = MfSystemObjectTable::register(array('created_class' => __CLASS__));
        $object5_0->addSubObject($object6_5);
        $object5_0->addSubObject($object11_5);
        $object8_0->addSubObject($object1_8);
        $object8_0->addSubObject($object12_8);
        $object8_0->addSubObject($object14_8);
        $object12_8->addSubObject($object2_12);
        $object12_8->addSubObject($object15_12);
        $object13_0->addSubObject($object3_13);
        $object13_0->addSubObject($object4_13);
        $object3_13->addSubObject($object9_3);
        $object4_13->addSubObject($object10_4);
        $arrayOfObjects[] = $object5_0;
        $arrayOfObjects[] = $object6_5;
        $arrayOfObjects[] = $object11_5;
        $arrayOfObjects[] = $object7_0;
        $arrayOfObjects[] = $object13_0;
        $arrayOfObjects[] = $object3_13;
        $arrayOfObjects[] = $object9_3;
        $arrayOfObjects[] = $object4_13;
        $arrayOfObjects[] = $object10_4;
        $arrayOfObjects[] = $object8_0;
        $arrayOfObjects[] = $object1_8;
        $arrayOfObjects[] = $object15_12;
        $arrayOfObjects[] = $object2_12;
        $arrayOfObjects[] = $object12_8;
        $arrayOfObjects[] = $object14_8;
        shuffle($arrayOfObjects);
        $array = MfSystemObjectTable::getTreeArray($arrayOfObjects);
        // проверяем количество корневых элементов.
        $this->assertEquals(4,count($array));
       
        $category1 = MfSystemObjectTable::register(array('created_class' => __CLASS__));
        $category2 =MfSystemObjectTable::register(array('created_class' => __CLASS__));
        $category1->getObject()->addSubObject($category2->getObject());
        $tree = MfSystemObjectTable::getTreeArray(array($category1,$category2));
        $treeEx[$category1->id] =
              array('object' => $category1,
                    'sub' => array(
                        $category2->id =>  array(
                             'object' => $category2,
                             'sub' => array(),
                             '_object' => $category2->getObject()
                        ),
                     ),
                    '_object' => $category1->getObject()
             );
        $this->assertEquals($treeEx,$tree);
    }

}
?>
