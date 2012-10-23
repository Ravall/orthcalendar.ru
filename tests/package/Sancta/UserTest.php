<?php
    require_once dirname(__FILE__) . '/ObjectSuite.php';
    require_once SANCTA_PATH . '/User.php';
    require_once PATH_LIBS . '/Mindfly/Date.php';

    class UserTest extends ObjectSuite {
        protected $model;
        protected $className = 'Sancta_User';

        public function setUp() {
            parent::setUp();
        }

        /**
         * тестируем создание пользователя
         */
        public function testCreate() {
            $email = TestTools::getRandomString() . '@email.com';
            $pass = TestTools::getRandomString();
            $this->model = Sancta_User::create(array(
                'login' => $email,
                'pass'  => $pass
            ));
            $result = $this->db->fetchRow(
                'select * from mf_system_user where id = ' . $this->model->getId()
            );
            $this->assertTrue(is_array($result));
            $userActual = $this->db->fetchAll(
                'Select email,pass from mf_system_user where id = ' . $this->model->getId()
            );
            $this->assertEquals(
                array('email' => $email,'pass' => md5($pass)),
                current($userActual)
            );
        }

        /**
         * проверяем получение пользователя по id
         */
        public function testGetById() {
            $this->model = Sancta_User::create(array(
                'login' => TestTools::getRandomString(),
                'pass' => TestTools::getRandomString()
            ));
            $model = Sancta_User::getById($this->model->getId());
            $this->assertEquals($this->model->getLogin(),$model->getLogin());
            $this->assertEquals($this->model->getPass(),$model->getPass());
            parent::testGetById($model);
        }

       /**
         * проверяем получение пользователя по email
         */
        public function testGetByEmail() {
            $user = Sancta_User::create(array(
                'login' => $email = TestTools::getRandomString(),
                'pass' => TestTools::getRandomString()
            ));
            $userActual = Sancta_User::getByLogin($email);
            $this->assertEquals($user, $userActual);

            $this->assertFalse(Sancta_User::getByLogin(TestTools::getRandomString()));
        }


        /**
         * тест функции проверки существование email
         */
        public function testIsExistEmail() {
            $email = TestTools::getRandomString() . '@email.com';
            // этого email не должно быть еще
            $this->assertFalse(Sancta_User::isExistEmail($email));
            $user = Sancta_User::create(array(
                'login' => $email,
                'pass' => TestTools::getRandomString()
            ));
            // теперь должен найтись
            $this->assertTrue(Sancta_User::isExistEmail($email));
            // проверка в контексте
            $this->assertFalse(Sancta_User::isExistEmail($email, $user->getId()));
        }

        /**
         * тестирование - генерации хеша
         */
        public function testCreateHash() {
            $user = Sancta_User::create(array(
                'login' => $email = TestTools::getRandomString(),
                'pass' => TestTools::getRandomString()
            ));
            $hash = $user->createHash();                        
            $userActual = current($this->db->fetchAll(
                'Select hash, hash_create from mf_system_user where id = ' . $user->getId()
            ));
            
            $this->assertEquals($hash, $userActual['hash']);
            
            $this->assertEquals(
                date('Y-m-d', time()),
                date('Y-m-d', strtotime($userActual['hash_create']))
            );
            $hash = $user->createHash();
            $userActual = current($this->db->fetchAll(
                'Select hash, hash_create from mf_system_user where id = ' . $user->getId()
            ));
            $this->assertEquals($hash, $userActual['hash']);
        }


        private function prepareDataSubscribe() {
           $userOne = Sancta_User::create(array(
                'login' => $email = TestTools::getRandomString(),
                'pass' => TestTools::getRandomString()
           ));
           $userTwo = Sancta_User::create(array(
                'login' => $email = TestTools::getRandomString(),
                'pass' => TestTools::getRandomString()
           ));
           $userThree = Sancta_User::create(array(
                'login' => $email = TestTools::getRandomString(),
                'pass' => TestTools::getRandomString()
           ));
           $userOne->subscribe($oneSubscribe1 = 1);
           $userOne->subscribe($oneSubscribe2 = 2);
           $userTwo->subscribe($twoSubscribe1 = 3);
           return array($userOne, $userTwo, $userThree, $oneSubscribe1, $oneSubscribe2, $twoSubscribe1);
        }



        public function testsSubsribe() {

           list($userOne, $userTwo, $userThree, $oneSubscribe1, $oneSubscribe2,
                $twoSubscribe1) = $this->prepareDataSubscribe();

           $result = $this->db->fetchAll(
               'select category_id from mf_calendar_user_category where user_id = '.$userOne->getId()
           );
           $this->assertEquals(2, count($result));
           $this->assertEquals($oneSubscribe1, $result[0]['category_id']);
           $this->assertEquals($oneSubscribe2, $result[1]['category_id']);

           $result = $this->db->fetchAll(
               'select category_id from mf_calendar_user_category where user_id = '.$userTwo->getId()
           );
           $this->assertEquals(1, count($result));
           $this->assertEquals($twoSubscribe1, $result[0]['category_id']);
           
           $result = $this->db->fetchAll(
               'select category_id from mf_calendar_user_category where user_id = '.$userThree->getId()
           );
           $this->assertEquals(0, count($result));
        }


        public function testIsSubsribe() {
            list($userOne, $userTwo, $userThree, $oneSubscribe1, $oneSubscribe2,
                 $twoSubscribe1) = $this->prepareDataSubscribe();
            $this->assertTrue($userOne->isSubscribe($oneSubscribe1));
            $this->assertTrue($userOne->isSubscribe($oneSubscribe2));
            $this->assertTrue($userTwo->isSubscribe($twoSubscribe1));
            $this->assertFalse($userOne->isSubscribe($twoSubscribe1));
            $this->assertFalse($userTwo->isSubscribe($oneSubscribe1));
            $this->assertFalse($userTwo->isSubscribe($oneSubscribe2));
            $this->assertFalse($userThree->isSubscribe($twoSubscribe1));
            $this->assertFalse($userThree->isSubscribe($oneSubscribe1));
            $this->assertFalse($userThree->isSubscribe($oneSubscribe2));
        }

        public function testUnSubscribe() {
            list($userOne, $userTwo, $userThree, $oneSubscribe1, $oneSubscribe2,
                 $twoSubscribe1) = $this->prepareDataSubscribe();
            $userOne->unSubscribe($oneSubscribe2);
            $this->assertTrue($userOne->isSubscribe($oneSubscribe1));
            $this->assertFalse($userOne->isSubscribe($oneSubscribe2));
            $this->assertTrue($userTwo->isSubscribe($twoSubscribe1));
            $this->assertFalse($userOne->isSubscribe($twoSubscribe1));
            $this->assertFalse($userTwo->isSubscribe($oneSubscribe1));
            $this->assertFalse($userTwo->isSubscribe($oneSubscribe2));
            $this->assertFalse($userThree->isSubscribe($twoSubscribe1));
            $this->assertFalse($userThree->isSubscribe($oneSubscribe1));
            $this->assertFalse($userThree->isSubscribe($oneSubscribe2));
        }

        /**
         * тестируем получение пользователей по gmt
         * среди тех, кто имеет хотя бы одну подписку
         */
        public function testGetUsersIdsByGmtAndHaveAnySubscribe() {
            $this->db->query('truncate mf_calendar_user_category');
            list($userOne, $userTwo, $userThree, $oneSubscribe1, $oneSubscribe2,
                 $twoSubscribe1) = $this->prepareDataSubscribe();
            $userOne->update(array('gmt' => $gmt = rand(10,20)));
            $userTwo->update(array('gmt' => $gmt));
            $userThree->update(array('gmt' => $gmt));
            $userTwo->subscribe($oneSubscribe2);
            $users = Sancta_User::getUsersByGmtAndHaveAnySubscribe($gmt);
            foreach ($users as $user) {
                $usersId[] = $user->getId();
            }
            $usersIdExcpected=array($userOne->getId(), $userTwo->getId());
            sort($usersIdExcpected);
            sort($usersId);
            $this->assertEquals($usersIdExcpected, $usersId);
        }


        public function testGetSubsribes() {
            list($userOne, $userTwo, $userThree, $oneSubscribe1, $oneSubscribe2,
                 $twoSubscribe1) = $this->prepareDataSubscribe();
            $this->assertEquals($userOne->getSubsribes(), array($oneSubscribe1,$oneSubscribe2));
            $this->assertEquals($userTwo->getSubsribes(), array($twoSubscribe1));
            $this->assertEquals($userThree->getSubsribes(), array());
        }

        /**
         * проверяет правильность механизма установки даты
         * @cover setDeliveryDone
         * @cover isDeliveryAlreadySend
         */
        public function testMarkDateDelivery() {
            list($userOne, $userTwo, $userThree, $oneSubscribe1, $oneSubscribe2,
                 $twoSubscribe1) = $this->prepareDataSubscribe();
            $date = new Mindfly_Date();
            $this->assertFalse($userOne->isDeliveryAlreadySend($oneSubscribe1, $date->getDay()));
            $this->assertFalse($userThree->isDeliveryAlreadySend($oneSubscribe1, $date->getDay()));
            $this->assertFalse($userTwo->isDeliveryAlreadySend($oneSubscribe1, $date->getDay()));
            $userOne->setDeliveryDone($oneSubscribe1,$date->getDay());
            $this->assertTrue($userOne->isDeliveryAlreadySend($oneSubscribe1, $date->getDay()));
            $this->assertFalse($userOne->isDeliveryAlreadySend($oneSubscribe1, $date->getNextDay()->getDay()));
            $this->assertFalse($userOne->isDeliveryAlreadySend($oneSubscribe2, $date->getDay()));
            $this->assertFalse($userThree->isDeliveryAlreadySend($oneSubscribe1, $date->getDay()));
            $this->assertFalse($userTwo->isDeliveryAlreadySend($oneSubscribe1, $date->getDay()));
            $userOne->setDeliveryDone($twoSubscribe1, $date->getDay());
            $this->assertFalse($userOne->isDeliveryAlreadySend($twoSubscribe1, $date->getDay()));

        }

        /**
         * тестировать создание пользователя без параметров нужно по другому
         * @return <type>
         */
        public function testCreateObjectTextNull(){}
        public function testCreateObjectTextNotNull(){}
        public function testChangeParams(){}
        public function testReplace(){}
        public function testDraft(){}


    }