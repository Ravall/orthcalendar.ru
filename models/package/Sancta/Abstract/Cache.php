<?php
require_once SANCTA_PATH . '/Abstract/Core.php';

abstract class Sancta_Abstract_Cache {
    
    
    CONST UPDATE_TAG_INDEX = 'update_tags';
    

    /**
     * Массив методов, которые должны быть закешированы
     */
    static protected $cachedMethods = array();    

    
    /**
     * Статическая перегрузка
     */
    public static function __callStatic($method, $args) {
        return self::__cacheCall($method, $args);       
    }
    
    /**
     * перегрузка
     */
    public function __call($method, $args) { 
        return self::__cacheCall($method, $args);               
    } 
    

    
    /**
     * call вызов. Стек перегрузки
     * 
     * @param type $method
     * @param type $args
     * @return type 
     */
    private static function __cacheCall($method, $args) {    
        $result = FALSE;
        $result === FALSE && $result = self::cacheMethod(get_called_class(), $method, $args);                          
        $result === FALSE && self::finalCall($method);
        return $result; 
    }

    /**
     * получить кеш-объект
     * 
     * @return type
     */
    public static function getCahce() {        
        $frontendOptions = array(
            'lifeTime' => 86400, // время жизни кэша - 24 часов
            'automatic_serialization' => TRUE,
        );
        $backendOptions = array(
            'cacheDir' => '/tmp/' // каталог, в котором размещаются файлы кэша
        );
        // создаем объект Zend_Cache
        $cache = Zend_Cache::factory(
                'Core', 'File', $frontendOptions, $backendOptions
        );
        return $cache;
    }
    
    /**
     * вызов кеширующего метода если, таковой имеется
     * 
     */
    private static function cacheMethod($object, $method, $args) {        
        if (!$cacheMethodParams = self::isCahceMethod($method)) {            
            return FALSE;
        }        
        return self::cahcedObjectMethod($object, $cacheMethodParams, $method, $args);        
    }
    
    
    /**
     * должен ли кешироваться метод
     * 
     * @param type $method
     * @return type 
     */
    private static function isCahceMethod($method) {        
        $cacheArray = static::$cachedMethods;
        if (!isset ($cacheArray[$method])) {
            return FALSE;
        }
        return $cacheArray[$method];
    }
    
    
     /**
     * вызываем функцию и кешируем результат
     * @param type $cache
     * @param type $method
     * @param type $args 
     */
    private static function cahcedObjectMethod($object, $cacheParams, $method, $args) {               
        $cache = self::getCahce(); 
        $cacheIndex = self::getCacheIndex($cacheParams, $args); 
        
        if (!$cache->test($cacheIndex)) {            
            $result = call_user_func_array(array($object, $method), $args);                               
            // сохраняем кеш, и устанавливаем теги                                           
            $cache->save($result, $cacheIndex, self::getCacheTags($result, $cacheParams));            
        } else {                        
            
            $result = $cache->load($cacheIndex);
        }
        return $result;
    }
    
    /**
     * формируем теги
     * 
     * @param type $result
     * @param type $cacheParams
     * @return type 
     */
    private function getCacheTags($result, $cacheParams) {
         $tags = isset($cacheParams['tags']) ? $cacheParams['tags'] : array();    
         return $tags;
    }

    


    private static function finalCall($method) {
        throw new Exception('method ' . $method . ' not find');
    }
    
    /**
     * формируем индекс кеша, на основании заготовленного индекса и параметров,
     * входящих в функцию
     * 
     * @param type $cacheParams
     * @param type $args
     * @return string 
     */
    private static function getCacheIndex($cacheParams, $args) {
       $index = $cacheParams['index'];        
       foreach ($args as $arg) {
           if ($arg instanceof Sancta_Bundle_StatusesParam) {
               $index = $index . '_' . $arg->getStatusesForCacheIndex();
           } elseif ($arg instanceof Mindfly_Date) {
               $index = $index . '_' . $arg->getDateByFormat('Ymd');
           } elseif (is_numeric($arg)) {
               $index = $index . '_' . $arg;
           } elseif(is_bool($arg)) {
               $index = $index . '_' . (int) $arg;
           } elseif(is_string($arg)) {
               $index = $index . '_' .  $arg;
           }
       }
       return $index;
    }

    /**
     * удаление кешированных данных по тегам
     * 
     * условие пересечение тегов - И
     * 
     * @param <type> $cacheArray
     */
    protected static function clearCachedListOfObject(array $cacheArray = array()) {
        $cache = self::getCahce();
        $cache->clean(
            Zend_Cache::CLEANING_MODE_MATCHING_TAG,
            $cacheArray
        );
    }   
   
    
}