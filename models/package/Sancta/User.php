<?php
/**
 * Модель пользователь
 */
require_once SANCTA_PATH . '/Abstract/System.php';
require_once dirname(__FILE__) . '/Object.php';
require_once PATH_BASE . '/models/Db/Mapper/SystemUser.php';
require_once SANCTA_PATH . '/List/User.php';

class Sancta_User extends Sancta_Abstract_System {
    protected $mapperTable = 'Db_Mapper_SystemUser';
    private $userId;
    private $email;
    private $pass;
    private $birthday;
    private $hash;
    private $hashCreate;
    private $gmt;
    private $dst;
    private $countryId;
    private $regionId;
    private $cityId;
    private $myCity;


    /**
     * параметры которые нужно обновлять через update
     * @var type
     */
    protected $modelParamKeys = array('email', 'pass', 'birthday', 'hash',
                                      'hash_create', 'gmt', 'dst','country_id',
                                      'region_id', 'city_id', 'my_city');
  
    
    public function getLogin() {
        return $this->email;
    }

    public function getPass() {
        return $this->pass;
    }

    public function getBirthday() {
        return $this->birthday;
    }

    public function getHash() {
        return $this->hash;
    }

    public function getHashCreate() {
        return $this->hashCreate;
    }

    /**
     * возращаем gmt
     * 
     * @return type 
     */
    public function getGmt() 
    {
        /**
         * если существует переход на летнее время
         */
        if ($this->getDst()) {
            $time = new Mindfly_Date();
            $dstTimeArray = SmartFunction::getDates('[01.05~31.05|0000001|-1]~[01.10~31.10|0000001|-1]',date('Y'));
            /**
             * если сейчас лето :-)
             */
            $isDstTime = in_array($time->getDay(), $dstTimeArray);
            $gmt = $isDstTime ? $this->gmt +1 : $this->gmt;
        } else {
            $gmt = $this->gmt;
        }
        return $gmt;
    }

    public function getCountryId() {
        return $this->countryId;
    }

    public function getRegionId() {
        return $this->regionId;
    }

    public function getCityId() {
        return $this->cityId;
    }

    public function getMyCity() {
        return $this->myCity;
    }
    
    public function getDst() {
        return $this->dst;
    }


        /**
     * проверям существование логина в базе
     * исключая $userId (исключение требуется для обновлений и валидаторов)
     * @param <type> $email
     * @param <type> $userId
     */
    public static function isExistEmail($email, $userId = 0) {
        $systemUserTable = new Db_Mapper_SystemUser();
        return $systemUserTable->isExistEmail($email, $userId);
    }

  



    /**
     * метод сохраняет объект в поля класса
     *
     * @param <type> $article
     */
    protected function _setModel($user) {
        $this->userId = $user->id;
        $this->email = $user->email;
        $this->pass = $user->pass;
        $this->birthday = $user->birthday;
        $this->hash = $user->hash;
        $this->hashCreate = $user->hash_create;
        $this->gmt = $user->gmt;
        $this->countryId = $user->country_id;
        $this->regionId = $user->region_id;
        $this->cityId = $user->city_id;
        $this->myCity = $user->mycity;
        $this->dst = $user->dst;
    }

    public function createHash() {
         $systemUserTable = new Db_Mapper_SystemUser();
         $systemUserTable->createHash($this->getId(), $hash = md5('mindfy' . time()));
         $this->hash = $hash;
         return $hash;
    }
    
    /**
     * проверяем, есть ли подписка
     * @param <type> $categoryId
     * @return <type>
     */
    public function isSubscribe($categoryId) {
         $subscribeTable = new Db_Mapper_CalendarUserCategory();     
         return $subscribeTable->isSubscribe($this->getId(), $categoryId);
    }


    /**
     * подписываемся
     * @param <type> $categoryId
     * @return <type>
     */
    public function subscribe($categoryId) {
        if (!$this->isSubscribe($categoryId)) {
            $subscribeTable = new Db_Mapper_CalendarUserCategory();
            $subscribeTable->subscribe($this->getId(), $categoryId);
            return true;
        }
        return false;
    }

    /**
     * отписываемся
     * 
     * @param <type> $categoryId
     * @return <type>
     */
    public function unSubscribe($categoryId) {
        if ($this->isSubscribe($categoryId)) {
            $subscribeTable = new Db_Mapper_CalendarUserCategory();            
            $subscribeTable->unsubscribe($this->getId(), $categoryId);
            return true;
        }
        return false;
    }

    public function getSubsribes() {
        $subscribeTable = new Db_Mapper_CalendarUserCategory();
        $categories = $subscribeTable->getSubsribe($this->getId());
        if (empty ($categories)) {
            return array();
        }
        $categoriesArray = array();
        foreach ($categories as $category) {
            $categoriesArray[] = $category['category_id'];
        }
        return $categoriesArray;
    }

    /**
     * помечает последний день рассылки
     * для категории $subscribeId днем $date
     * 
     * @param <type> $subscribeId
     * @param <type> $date
     */
    public function setDeliveryDone($subscribeId, $date) {
         $subscribeTable = new Db_Mapper_CalendarUserCategory();
         $subscribeTable->setDeliveryDone($this->getId(), $subscribeId, $date);
    }

    /**
     * проверяет была ли в указанный днеь рассылка
     * 
     * @param <type> $subscribeId
     * @param <type> $date
     */
    public function isDeliveryAlreadySend($subscribeId, $date) {
        $subscribeTable = new Db_Mapper_CalendarUserCategory();
        return $subscribeTable->isDeliveryAlreadySend($this->getId(), $subscribeId, $date);
    }

    public function setGmtRaw($gmtRaw) {
        list($offset, $dst) = explode(',', $gmtRaw);
        list($h,$m) = explode(':', $offset);
        $h = (int) $h;
        $m = (int) $m;
        $dst = (int) $dst;
        $this->update($x = array(
            'gmt' => $h+($m/60),
            'dst' => $dst
        ));
    }

}