<?php
require_once SANCTA_PATH . '/Abstract/List.php';
require_once SANCTA_PATH . '/Peer/Event.php';

class Sancta_List_Event extends Sancta_Abstract_List {    
    protected $classNamePeer = 'Sancta_Peer_Event';  
    protected $listClass = 'Sancta_List_Event';
    const TODONOTE_SEPARATOR = "\n";
    
    /**
     * группируем события
     * groupBy - группировка событий. Массив группировок. 
     * 
     * @param type $eventList
     * @param type $groupBy 
     */
    public function groupBy($groupBy) {
        $groups = array();
        foreach ($this->item as $event) {
            $relatedObjects = $event->getRelatedObjets();
            foreach ($groupBy as $groupKey => $groupsArray) {
                foreach (array_intersect($relatedObjects, $groupsArray) as $group) {
                    if (in_array($group, $groupsArray)) {
                        $groups[$groupKey][] = $event->getId();
                    }
                }
            }
        }                
        foreach ($groups as $groupKey => $groupsArray) {
            $groups[$groupKey] = $this->createFiltered($groups[$groupKey]);
        }
        return $groups;
    }
    
    
    /**
     *
     * @param type $remark
     * @return type 
     */
    public function getToDoNotes() {
        $result = array();
        foreach ($this->item as $event) {             
            $result = array_merge($result, $event->getToDo());
        }
        return $result;
    }
    
    /**
     * Выделяем список переодичных событий
     */
    public function getPeriodicEvents($is = true) {        
        return $this->filter('isPeriodic', $is);        
    }
    
    
    /**
     * выделяем события категории
     * 
     * @param type $category
     * @param type $is
     * @return type 
     */
    public function getCategory($category, $is = true) {
        return $this->filter('isInCategory', $is, $category);        
    }

    /**
     * получить массив ссылок
     * 
     * @return <type>
     */
    public function getLinkArray() {
        $links = array();
        foreach ($this->item as $event) {
            $links[] = $event->getLink($event->getTitle());
        }
        return $links;
    }
    
    /**
     * выдергиваем все статьи с полученных событий
     * 
     */
    public function getArticles()
    {
        $articleIds = $articleArray = array();
        foreach ($this->item as $event) {
            $articles = Sancta_Peer_Article::getByEventId($event->getId());
            foreach ($articles as $article) {
                if (!in_array($article->getId(), $articleIds)) {
                    $articleIds[] = $article->getId();
                    $articleArray[] = $article;
                }
            }
        } 
        return $articleArray;
    }
    
    
}
?>