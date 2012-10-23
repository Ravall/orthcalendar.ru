<?php
class TestTools 
{
    /**
     * ������� ��������� ������
     **/
    public static function getRandomString($prefix='') {
        if (!empty($prefix)) {
            $prefix .= '_';
        }
        return uniqid($prefix . self::getTestPrefix() . '_');
    }
    
    /**
     * �������� �������, ����������� �������� ������ � ��������� 
     * ������� ��������
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
    * ������� �������� ������� �����
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
