<?php
require_once SANCTA_PATH . '/Abstract/Cache.php';

class IndexController extends Zend_Controller_Action
{

    public function indexAction() {}

    /**
     * очищение кеша
     */
    public function clearcacheAction() {
        $cache = Sancta_Abstract_Cache::getCahce();
        $cache->clean(Zend_Cache::CLEANING_MODE_ALL);
        $this->_redirect('/');
    }



}

