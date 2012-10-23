<?php
    interface Sancta_Interface_Template {       
        /**
         * Получить шаблон по id
         */
        public static function getById($id);
        /**
         * создать шаблон
         */
        public static  function create($params);
        /**
         *  получить все шаблоны в зависимости от статуса
         */
        public static function getAll($statuses);
        /**
         * получить заглавие шаблона
         */
        public function getTitle();
        /**
         * Получить содержание шаблона
         */
        public function getContent();
        /**
         * установить новый заголовок шаблона
         */
        public function setTitle($text);
        /**
         * установить новое содержание шаблона
         */
        public function setContent($text);

    }
?>
