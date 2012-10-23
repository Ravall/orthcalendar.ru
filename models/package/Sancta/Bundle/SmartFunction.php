<?php
require_once PATH_BASE . '/models/Db/Mapper/CalendarSmartFunction.php';
require_once PATH_BASE . '/models/Db/Mapper/CalendarNet.php';
require_once PATH_BASE . '/models/package/SmartFunction/SmartFunction.php';

/**
 * Класс для работы с умными функциями
 */

class Sancta_Bundle_SmartFunction {
    
    private $id;
    private $reload = null;
    private $smartFunction = null;
    
    const LIMIT = 50;
    

    public static function create($function) {
        $functionTable = new Db_Mapper_CalendarSmartFunction();
        return $functionTable->create($function);        
    }
    
    public function __construct($functionId) {
        if ($functionId) {
            $functionTable = new Db_Mapper_CalendarSmartFunction();
            $function = $functionTable->getById($functionId);    
            $this->_setFunction($function);
        }
    }
    
    public function getReload() {
        return $this->reload;
    }
    
    public function getSmartFunction() {
        return $this->smartFunction;
    }
    
    public function update($params) {        
        if (isset($params['smart_function']) && $params['smart_function'] != $this->getSmartFunction()) {
            $functionTable = new Db_Mapper_CalendarSmartFunction();
            $params['reload'] = 1;            
            $function = $functionTable->update($this->id, $params, array('smart_function','reload'));   
            $this->_setFunction($function);
        }
    }


    private function _setFunction($function) {        
        $this->id = $function->id;
        $this->reload = $function->reload;
        $this->smartFunction = $function->smart_function;
    }
    
    /**
     * построение карты времени для перезагруженных функций
     */
    public static function createSmartFunctionDataMap() {        
        $configLimit = Config_Interface::get('limit', 'reloadSmartFunction');
        $configYearBegin = Config_Interface::get('yearBegin', 'reloadSmartFunction');
        $configYearEnd = Config_Interface::get('yearEnd', 'reloadSmartFunction');
        $functionTable = new Db_Mapper_CalendarSmartFunction();
        $functionTable->beginTransaction();
        try {
            /**
             * получаем пачку перезагруженных формул
             */
            $reloads = $functionTable->getReloaded($configLimit);                
            $net = new Db_Mapper_CalendarNet();
            foreach ($reloads as $reload) {       
                /**
                 * очищаем сгенерированную карту для данной функции
                 */
                $net->removeByFunctionId($reload['id']);         
                $net->addStart();            
                for ($year = $configYearBegin; $year <= $configYearEnd; $year++) {
                    $result = array();
                    $fullDateArray = SmartFunction::getDates($reload['smart_function'], $year, $result);             
                    $net->addNet($reload['id'], $result, $fullDateArray);
                    $functionTable->setReloaded($reload['id']);
                }
                $net->addEnd();
            }
            $functionTable->commitTransaction();
        } catch (Exception $e) {
            $functionTable->rollBackTransaction();
            throw $e;
        }
        
    }
    
    
    
    
}
?>