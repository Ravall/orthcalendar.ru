<?php
require_once PATH_BASE . '/models/Db/Mapper/SystemMailStack.php';
require_once PATH_LIBS_ZEND . '/Zend/Config/Ini.php';
require_once PATH_LIBS_ZEND . '/Zend/Mail.php';

/* 
 * Класс для работы с почтой
 */
class Sancta_Peer_Mail {

    /**
     * функция отправляет почту,
     * выбирая заданное число писем из подготовленного стека
     */
    public static  function sendMail() 
    {
        $mailStack = new Db_Mapper_SystemMailStack();
        $mailStack->beginTransaction();
        try {
            $config = new Zend_Config_Ini(PATH_BASE . '/config/config.ini', 'mail');
            $stackLimit = $config->stackLimit;
            $mailFrom = $config->mailFrom;            
            $mailArray = $mailStack->getNotSendMail($stackLimit);
            foreach ($mailArray as $mail) {             
                $mailSend = new Zend_Mail('utf-8');
                $mailSend->setBodyHtml($mail->text);
                $mailSend->setFrom($mailFrom, 'OrthCalendar.ru');
                $mailSend->addTo($mail->to);
                $mailSend->setSubject($mail->subject);
                $mailStack->setSended($mail->id);
                $mailSend->send();        
            }
            $mailStack->commitTransaction();
        } catch (Exception $e) {
            $mailStack->rollBackTransaction();
            throw $e;
        }
    }

    public static function addMailToStack($to, $subject, $text) {
        $mailStack = new Db_Mapper_SystemMailStack();
        return $mailStack->addMail($to, $subject, $text);
    }

    public static function addMailToAdmin($subject, $text) {
        $config = new Zend_Config_Ini(PATH_BASE.'/config/config.ini', 'mail');
        return self::addMailToStack($config->adminMail, $subject, $text);
    }
}

?>