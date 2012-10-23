<?php
    require_once dirname(__FILE__) . '/SanctaTestCase.php';
    require_once SANCTA_PATH . '/Mail.php';

    class MailTest extends SanctaTestCase {
        public function testSendMail() {
            $this->db->query('Delete from mf_system_mail_stack');
            $to = TestTools::getRandomString();
            $subject = TestTools::getRandomString();
            $text = TestTools::getRandomString();
            $id = Sancta_Mail::addMailToStack($to, $subject, $text);
            Sancta_Mail::sendMail();
            $result = $this->db->fetchRow(
               'select * from mf_system_mail_stack where id = ' . $id
            );
            $this->assertEquals(1, $result['is_send']);
        }

        public function testSendMailLimits() {
            $this->db->query('Delete from mf_system_mail_stack');
            $config = new Zend_Config_Ini(PATH_BASE.'/config/config.ini', 'mail');
            for (${0} = 0; ${0} < $config->stackLimit+3; ${0}++) {
                Sancta_Mail::addMailToStack(
                    TestTools::getRandomString(),
                    TestTools::getRandomString(),
                    TestTools::getRandomString()
                );
            }
            Sancta_Mail::sendMail();
            $result = $this->db->fetchAll(
               'select is_send, count(*) cnt from mf_system_mail_stack group by is_send'
            );
            foreach ($result as $row) {
                $resultArray[$row['is_send']] = $row['cnt'];
            }
            $this->assertEquals(3, $resultArray[0]);
            $this->assertEquals($config->stackLimit, $resultArray[1]);
        }


        /**
         * @dataProvider providerAddMailToStack
         */
        public function testAddMailToStack($to,$subject,$text,$toExp,$subjectExp,$textExp,$isSend) {
            $id = Sancta_Mail::addMailToStack($to, $subject, $text);
            $result = $this->db->fetchRow(
               'select * from mf_system_mail_stack where id = ' . $id
            );
            $this->assertEquals($toExp, $result['to']);
            $this->assertEquals($subjectExp, $result['subject']);
            $this->assertEquals($textExp, $result['text']);
            $this->assertEquals($isSend, $result['is_send']);
        }

        public function providerAddMailToStack() {
            return array(
                array(
                    $to = TestTools::getRandomString(),
                    $subject = TestTools::getRandomString(),
                    $text = TestTools::getRandomString(),
                    $to,
                    $subject,
                    $text,
                    0),
            );
        }

        public function testAddMailToAdmin() {
            $subject = TestTools::getRandomString();
            $text = TestTools::getRandomString();
            $id = Sancta_Mail::addMailToAdmin($subject, $text);
            Sancta_Mail::sendMail();
            $result = $this->db->fetchRow(
               'select * from mf_system_mail_stack where id = ' . $id
            );
            $config = new Zend_Config_Ini(PATH_BASE.'/config/config.ini', 'mail');
            $this->assertEquals($config->adminMail, $result['to']);
        }
    }
?>