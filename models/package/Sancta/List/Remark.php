<?php
require_once SANCTA_PATH . '/Abstract/List.php';
require_once SANCTA_PATH . '/Peer/Remark.php';
require_once SANCTA_PATH . '/Remark.php';

class Sancta_List_Remark extends Sancta_Abstract_List {    
    protected $classNamePeer = 'Sancta_Peer_Remark';  
    
    /**
     * получить из списка ремарков, ремарк с наивысшим приоритетом
     */
    public function getMaxPriorityRemark() {
        if (empty($this->item)) {
            return false;
        }
        $maxPriorityRemark = $this->item[0];
        foreach ($this->item as $item) {
            if ($maxPriorityRemark->getPriority() < $item->getPriority()) {
                $maxPriorityRemark = $item;
            }
        }
        return $maxPriorityRemark;
        
    }
}
?>