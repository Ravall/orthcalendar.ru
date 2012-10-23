<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MfSystemTextTable
 *
 * @author user
 */
class MfSystemTextTable extends Zend_Db_Table_Abstract {
    const TITLE = 'title';
    const ANNONCE = 'annonce';
    const CONTENT = 'content';


    protected $_name = 'mf_system_text';
    protected $_primary = 'id';
    // класс строки
    protected $_rowClass = 'MfSystemTextRow';
    // зависимые таблицы
    protected $_dependentTables = array('MfSystemObjectTextTable');
}
?>
