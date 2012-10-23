<?php
/**
 * Модель статья
 */
require_once SANCTA_PATH . '/Abstract/System.php';
require_once dirname(__FILE__) . '/Object.php';
require_once dirname(__FILE__) . '/Interface/Article.php';
require_once PATH_BASE . '/models/Db/Mapper/SystemArticle.php';
require_once PATH_BASE . '/models/Db/Mapper/SystemRelation.php';

class Sancta_Article extends Sancta_Abstract_System {
    const RELATE_TO_EVENT_TYPE = 1;

    /**
     * поля класса
     * @var <type> 
     */
    private $articleId = null;
    
    protected $mapperTable = 'Db_Mapper_SystemArticle';

   

    /**
     * привязать статью к событию
     *
     * @param <type> $eventId
     * @return <type>
     */
    public function relateToEvent($eventId) {
        $systemRelationTable = new Db_Mapper_SystemRelation();
        $result =  $systemRelationTable->relate($this->getId(), $eventId, self::RELATE_TO_EVENT_TYPE);
        return $result;    
    }

    /**
     * Устанавливает массив связей
     *
     * @param <type> $array
     */
    public function setRelates($array) {
        $related = $this->getRelatedEvents();
        array_walk($array, create_function('&$val', '$val = trim($val);'));        
        sort($related);
        sort($array);
        $diff1 = array_diff($related, $array);
        $diff2 = array_diff($array, $related);
        if (!empty($diff1) || !empty($diff2)) {            
            $systemRelationTable = new Db_Mapper_SystemRelation();
            $systemRelationTable->setRelates($this->getId(), $array);                       
        } 
        return true;        
    }

    public function getRelatedEvents() {
        return $this->getRelatedObjets();
    }

    /**
     * метод сохраняет объект в поля класса
     * 
     * @param <type> $article
     */
    protected function _setModel($article) {
        $this->articleId = $article->id;
    }
    
        
    public function getLink($text = false, $options = array()) {

        $bootstrap = Zend_Controller_Front::getInstance()
                   ->getParam('bootstrap');
        if ($bootstrap === null) {
            $application = new Zend_Application(
                APPLICATION_ENV, APPLICATION_PATH . '/config/application.ini'
            );
            $application->bootstrap();
            $bootstrap = $application->getBootstrap();
        }
        
        $view = $bootstrap->getResource('view');
        
        $url = $view->url(
                array(
                    'id' => $this->getId(),
                    'event_id' => isset($options['event_id']) 
                                ? $options['event_id'] 
                                : false, 
                ), 'article_event');  
        if ($text) {
            $link = "<a href='{$url}'>{$text}</a>";
        }   else {
            $link = $url;
        }   
        return $link;
    }
    
    

    public function getToDo() {
        $result = array();
        if ($annonce = $this->getAnnonce()) {
            $explode = explode(Sancta_List_Event::TODONOTE_SEPARATOR, $annonce);
            foreach ($explode as $link) {
                if ($link) {
                    $result[] = $this->getLink($link);
                }
            }
        }
        return $result;
    }


    
}