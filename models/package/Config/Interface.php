<?php
/**
 * по установленному окружению грузим все конфиги в следующей последовательности
 * 
 * base + папки окружения
 */

require_once PATH_LIBS_ZEND . '/Zend/Config/Ini.php';

class Config_Interface {        
    const REGISTRY_ENV_INDEX = 'environment';
    
    public static function get($key, $section) {         
        if (!self::getEnvironment()) {
            self::setEnvironment();
        }        
        $config = self::_generateResultConfig();              
        return $config->$section->$key;
        
    }
        
    public static function getEnvironment() {
        if (!Zend_Registry::isRegistered(self::REGISTRY_ENV_INDEX)) {
            return '';
        }
        return Zend_Registry::get(self::REGISTRY_ENV_INDEX);
    }
    
    public static function setEnvironmentPart($part) {
        $env = self::getEnvironment();
        $env = $env ? ($env . '/') : '';        
        self::setEnvironment($env . $part);        
    }
    
    public static function setEnvironment($env = false) {
        if (!$env) {
            $env = self::getDefaultEnvironment();
        }
        Zend_Registry::set(self::REGISTRY_ENV_INDEX, $env);
    }
    
    public static function isProduction() {        
        return (bool) (self::getEnvironment() === 'production');
    }
    
    public static function getDefaultEnvironment() {
        return getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production';
    }
    
    public static function _generateResultConfig() {
        $configBase = new Zend_Config_Ini(PATH_BASE . '/config/init.ini', null, true);                
        self::_loadFolder('base', $configBase);
        foreach (explode('/', self::getEnvironment()) as $folder) {   
            if ($folder = trim($folder)) {
                self::_loadFolder($folder, $configBase);
            }
        }
        $configBase->setReadOnly();
        return $configBase;
    }
    
    private static function _loadFolder($folder, &$Configbase) {
        $result = shell_exec('ls ' . PATH_BASE . '/config/' . $folder . '/');
        $files = explode("\n", $result);
        foreach ($files as $file) {
            if ($file = trim($file)) {
                $Configbase->merge(new Zend_Config_Ini(PATH_BASE . '/config/' . $folder . '/' . $file));
            }
        }        
    }
}