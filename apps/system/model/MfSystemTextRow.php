<?php
/**
 * Модель поведения текста
 *
 * @author user
 */
class MfSystemTextRow extends Zend_Db_Table_Row_Abstract {

    /**
     * проверяем пустой ли текст содержит объект
     * @return bool
     */
    public function isEmpty() {
        return !(bool) ($this->title . $this->content . $this->annonce);
    }

    /**
     * обновляем содержимое
     * 
     * @param <type> $params
     */
    public function updateText($params) {
        if (isset($params['title'])) {
            $this->title = $params['title'];
        }
        if (isset($params['annonce'])) {
            $this->annonce = $params['annonce'];
        }
        if (isset($params['content'])) {
            $this->content = $params['content'];
        }
        $this->save();
    }

    public function getContent() {
        $this->content = str_replace("\n", '</p><p>', $this->content);
        return '<p>'.$this->content.'</p>';
    }

    /**
     * Убираем скобки, последнюю точку
     */
    public function getTitle() {
        $title = $this->title;
        $title = preg_replace('/\(.*?\)/', '', $title);
        $title = str_replace(".", '', $title);
        return trim($title);
    }

}
?>