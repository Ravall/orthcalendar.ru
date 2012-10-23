<?php
require_once PATH_BASE . '/models/Db/Mapper/SystemText.php';
require_once PATH_BASE . '/models/Db/Mapper/SystemObjectText.php';
require_once dirname(__FILE__) . '/Abstract/Cache.php';

/**
 * сущность текст
 */
class Sancta_Text extends Sancta_Abstract_Core {


    private
        $title,
        $annonce,
        $content,
        $textId;
    
    private $textParamKeys = array('title','annonce','content');

    const TITLE = 'title';
    const ANNONCE = 'annonce';
    const CONTENT = 'content';

    const DRAFT = 'draft_index_';

    public function  __construct() {
        parent::__construct();                
    }

    protected function  _create($params) {
        
        $systemText = new Db_Mapper_SystemText();
        $systemObjectText = new Db_Mapper_SystemObjectText();
        $text = $systemObjectText->getText($this->getId());
        
        if (!$this->getId() ||  !$text) {
            /**
             * если текста нет - то добавляем и связываем
             */            
            $text = $systemText->create();
            $systemObjectText->joinText($this->getId(), $text->id, 'active');
        }                
        $this->_setText($text);   
        $this->updateText($params);        
    }


    protected function _getById($id) {
        $systemObjectText = new Db_Mapper_SystemObjectText();
        $text = $systemObjectText->getText($id);
        if (!$text) {
            /**
             * если текста нет - то добавляем и связываем
             * В принципе не должно сработать эта часть кода, но
             * на всякий случай добавляем.
             */
            $systemText = new Db_Mapper_SystemText();
            $text = $systemText->create();
            $systemObjectText->joinText($id, $text->id, 'active');
        }
        $this->_setText($text);        
    }

    

    /**
     * получает содержание, которое может быть заполнено метками.
     * 
     * @param <type> $replace
     * @return <type>
     */
    public function getContent($replace = array()) {
        if (!empty ($replace)) {
            $this->content = str_replace(array_keys($replace), array_values($replace), $this->content);
        }
        return $this->content;
    }

    
    public function getAnnonce() {
        return $this->annonce;
    }
    
    public function getTitle() {
        return $this->title;
    }

    public function setContent($text) {
        return $this->update(array(self::CONTENT => $text));
    }

    public function setAnnonce($text) {
        return $this->update(array(self::ANNONCE => $text));
    }

    public function setTitle($text) {
        return $this->update(array(self::TITLE => $text));
    }

  
    private function _setText($text) {
        $this->title = $text->title;
        $this->annonce = $text->annonce;
        $this->content = $text->content;
        $this->textId = $text->id;
    }

    /**
     * обновляем параметры текста
     * 
     * @param type $param 
     */
    public function updateText($param) {        
        $table = new Db_Mapper_SystemText();
        $model = $table->update($this->textId, $param, $this->textParamKeys);
        $this->_setText($model);
    }    
    public function update($param) {
        $this->updateText($param);
    }


}