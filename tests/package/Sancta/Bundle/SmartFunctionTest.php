<?php
require_once dirname(__FILE__) . '/../SanctaTestCase.php';    
require_once PATH_LIBS_ZEND . '/Zend/Config/Ini.php';
require_once SANCTA_PATH . '/Bundle/SmartFunction.php';

class SmartFunctionTest extends SanctaTestCase {
    
    public function testCreateSmartFunctionDataMap() {
         $config = new Zend_Config_Ini(dirname(__FILE__) . '/SmartFunctionTest/config.ini','net');
         /**
          * Удаляем все что есть в карте
          */
         $this->db->query('delete from mf_calendar_net');
         $this->db->query('delete from mf_calendar_smart_function');
         $functionId = Sancta_Bundle_SmartFunction::create('12.01~15.01');
         
         
         // пусть в базе что-то есть
         $this->db->query(
             'insert into mf_calendar_net (day,month,year,full_date,function_id) VALUES
                 (12, 1, 2000, "2000-01-12",'. $functionId.'),
                 (13, 1, 2000, "2000-01-12",111)
         ');
         /**
          * генерим карту
          */
         Sancta_Bundle_SmartFunction::createSmartFunctionDataMap($config);
         $fetch = $this->db->fetchRow('select count(*) cnt from mf_calendar_net where function_id = 111');         
         $this->assertTrue($fetch['cnt']>0, 'карты по другой функции должны были остаться');
         $fetch = $this->db->fetchRow('select count(*) cnt from mf_calendar_net where year = 2000 and function_id = ' . $functionId);         
         $this->assertTrue($fetch['cnt'] == 0, 'Старая карта обновленной функции должна сохраниться');
         $fetch = $this->db->fetchRow('select count(*) cnt from mf_calendar_net where function_id = ' . $functionId);         
         $this->assertEquals(8, $fetch['cnt'], 'Неверное количество полученных дат');
         $functionRow = $this->db->fetchRow('select * from mf_calendar_smart_function where id = '.$functionId);
         $this->assertEquals(0, $functionRow['reload']);
         
         
    }
}
