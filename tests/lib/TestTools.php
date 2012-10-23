<?php
class TestTools 
{
    /**
     * создает случайную строку
     **/
    public static function getRandomString($prefix='') {
        if (!empty($prefix)) {
            $prefix .= '_';
        }
        return uniqid($prefix . self::getTestPrefix() . '_');
    }
    
    /**
     * Получить префикс, маркирующий тестовые данные с указанием 
     * времени создания
     **/
    public static function getTestPrefix() {
        return 'test_' . time() . '_';
    }
    
    public static function getTestUserId() {
        return 1;
    }
    
    public static function getTestData() {
        return '1983-11-03 23:00:00';
    } 

   /**
    * генерим случайны простой текст
    **/
   public static function getRandomSimpleText() {
             $title = self::getRandomString();
             $annonce = self::getRandomString();
             $content = self::getRandomString();
             return  array('title'=>$title,
                                    'annonce' => $annonce,
                                    'content' => $content);
   }

}
