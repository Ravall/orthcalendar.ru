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
class Db_Gateway_SystemText extends Zend_Db_Table_Abstract {


    protected $_name = 'mf_system_text';
    protected $_primary = 'id';
    // класс строки
    protected $_rowClass = 'MfSystemTextRow';
    // зависимые таблицы
    protected $_dependentTables = array('Db_Gateway_SystemObjectText');
}