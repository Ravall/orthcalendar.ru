<?php
    require_once dirname(__FILE__) . '/Abstract.php';
    require_once PATH_BASE . '/models/Db/Gateway/SystemText.php';
    
    class Db_Mapper_SystemText extends Db_Mapper_Abstract {

        public function  __construct() {
            $this->setDbTable('Db_Gateway_SystemText');
        }

        /**
         * создаем запись в таблице
         *
         * @param <type> $params
         * @return <type>
         */
        public function create($params = array()) {
            $text = $this->getDbTable()->createRow();
            $text->title = isset($params['title']) ? $params['title'] : '';
            $text->annonce = isset($params['annonce']) ? $params['annonce'] : '';
            $text->content = isset($params['content']) ? $params['content'] : '';
            $text->save();
            return $text;
        }

        #/**
        # * обновляем таблицу
        # * 
        # * @param <type> $params
        # */
        #public function update($textId, $params) {
        #    $text = $this->getDbTable()->find($textId)->current();
        #    if (isset($params['title'])) {
        #        $text->title = $params['title'];
        #    }
        #    if (isset($params['annonce'])) {
        #        $text->annonce = $params['annonce'];
        #    }
        #    if (isset($params['content'])) {
        #        $text->content = $params['content'];
        #    }
        #    $text->save();
        #    return $text;
        #}

       /**
        * проверяем пустой ли текст содержит объект
        * @return bool
        */
        public function isEmpty() {
            return !(bool) ($this->getDbTable()->title . $this->getDbTable()->content . $this->getDbTable()->annonce);
        }

      
    }