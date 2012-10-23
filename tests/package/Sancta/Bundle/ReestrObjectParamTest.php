<?php
require_once dirname(__FILE__) . '/../SanctaTestCase.php';
require_once PATH_LIBS_ZEND . '/Zend/Config/Ini.php';
require_once SANCTA_PATH . '/Peer/Article.php';

class ReestrObjectParamTest extends SanctaTestCase {
    public function testSet() {
        $model = Sancta_Peer_Article::create();
        $this->assertFalse($model->getReestrParam($key = 'test1/test2'));
        $model->setReestrParam($key, $val = 123);
        $row = $this->db->fetchRow('select * from mf_system_registry where objectId = '. $model->getId());
        $this->assertEquals($key, $row['key']);
        $this->assertEquals($val, $row['value']);
        $this->assertEquals($val, $model->getReestrParam($key));
        $model->setReestrParam($key, $val = 'xxx/xx"sss');
        $model->setReestrParam($key2 = '2', $val2 = '+');
        $this->assertEquals($val, $model->getReestrParam($key));
        $params = $model->getReestrParams();
        $this->assertEquals($val, $params[$key]);        
        $this->assertEquals($val2, $params[$key2]);

    }

}