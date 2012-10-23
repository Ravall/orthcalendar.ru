<?php
    require_once dirname(__FILE__) . '/SanctaTestCase.php';    
    class textSuite extends SanctaTestCase {

        protected function _testText($model) {
            $this->createObjectTextNull($model);
            $this->changeParams($model);            
            $this->replace($model);
        }

        /**
         * тестируем создание объкта без параметров
         */
        private function createObjectTextNull($model) {
            $this->assertTrue($model->getId() > 0);
            $this->assertEquals('', $model->getContent());
            $this->assertEquals('', $model->getAnnonce());
            $this->assertEquals('', $model->getTitle());
            $result = $this->db->fetchRow(
                'select t.* from mf_system_text t '
              . ' left join mf_system_object_text st '
              . ' ON t.id = st.system_text_id '
              . ' WhERE st.lang = ? and st.system_object_id = ?',
                  array(LANG_DEFAULT, $model->getId())
            );
            $this->assertEquals($result['title'], '');
            $this->assertEquals($result['annonce'], '');
            $this->assertEquals($result['content'], '');
        }

        /**
         * Тестируем создание объекта с заданными параметрами
         */
        public function _testCreateObjectTextNotNull($model, $content, $title) {
            $this->assertTrue($model->getId() > 0);
            $this->assertEquals($content, $model->getContent());
            $this->assertEquals('', $model->getAnnonce());
            $this->assertEquals($title, $model->getTitle());
            $result = $this->db->fetchRow(
                'select t.* from mf_system_text t '
              . ' left join mf_system_object_text st '
              . ' ON t.id = st.system_text_id '
              . ' WhERE st.lang = ? and st.system_object_id = ?',
                  array(LANG_DEFAULT, $model->getId())
            );           
            $this->assertEquals($result['title'], $title);
            $this->assertEquals($result['annonce'], '');
            $this->assertEquals($result['content'], $content);
        }

        /**
         * Тестируем изменение параметров
         */
        private function changeParams($model) {
            $model->setContent($content = TestTools::getRandomString());
            $this->assertEquals($content, $model->getContent());
            $model->setContent($content = TestTools::getRandomString());
            $this->assertEquals($content, $model->getContent());
            $model->setAnnonce($annonce = TestTools::getRandomString());
            $this->assertEquals($annonce, $model->getAnnonce());
            $model->setAnnonce($annonce = TestTools::getRandomString());
            $this->assertEquals($annonce, $model->getAnnonce());
            $model->setTitle($title = TestTools::getRandomString());
            $this->assertEquals($title, $model->getTitle());
            $model->setTitle($title = TestTools::getRandomString());
            $this->assertEquals($title, $model->getTitle());
            $result = $this->db->fetchRow(
                'select t.* from mf_system_text t '
              . ' left join mf_system_object_text st '
              . ' ON t.id = st.system_text_id '
              . ' WhERE st.lang = ? and st.system_object_id = ?',
                  array(LANG_DEFAULT, $model->getId())
            );
            $this->assertEquals($title, $result['title']);
        }

        public function replace($model) {
            $model->setContent('i am %name%. I am go to %place% and funny');
            $content = $model->getContent(array(
                '%name%' => 'ravall',
                '%place%' => 'west'
            ));
            $this->assertEquals('i am ravall. I am go to west and funny', $content);
        }

       


    }