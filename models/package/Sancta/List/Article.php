<?php
require_once SANCTA_PATH . '/Abstract/List.php';
require_once SANCTA_PATH . '/Article.php';
require_once SANCTA_PATH . '/Peer/Article.php';

class Sancta_List_Article extends Sancta_Abstract_List {
    protected $className = 'Sancta_Article';    
    protected $classNamePeer = 'Sancta_Peer_Article';
    protected $listClass = 'Sancta_List_Article';    

    /**
     * получить массив ссылок
     *
     * @return <type>
     */
    public function getLinkArray() {
        $links = array();
        foreach ($this->item as $article) {
            $links[] = $article->getLink($article->getTitle());
        }
        return $links;
    }
}
?>