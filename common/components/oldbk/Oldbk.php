<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 22.11.2014
 * Time: 22:27
 */

namespace common\components\oldbk;

use common\components\VarDumper;
use Yii;
use CJSON;
class Oldbk extends \CApplicationComponent
{
    public $auth_link = null;
    public $info_link = null;
    public $bank_info_link = null;
    public $money_info_link = null;
    public $gold_info_link = null;
    public $bank_auth_link = null;
    public $cr_operation_link = null;
    public $ekr_operation_link = null;
    public $gold_operation_link = null;

    public $api_key = null;

    public function init()
    {
        Yii::app()->curl->get('http://oldbk.com');
    }

    public function beforeAction()
    {
        Yii::app()->curl->setOptions([
            CURLOPT_COOKIEJAR => ROOT_DIR.'/cookie/oldbk.txt',
            CURLOPT_COOKIEFILE => ROOT_DIR.'/cookie/oldbk.txt',
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13',
        ]);
    }

    private static $voucher_link = 'http://capitalcity.oldbk.com/blog_api_ko_list.php?key=I9RdXHeFYNlufui3TrRZ38U8&owner=';
    private static $voucher_link_pay = 'http://capitalcity.oldbk.com/blog_api_ko_pay.php';

    /**
     * @param $game_id
     * @param bool $force
     * @return Vouchers|mixed
     *
     * @deprecated
     */
    public static function getVouchers($game_id, $force = false)
    {
        $voucher = Yii::app()->cache->get('voucher_'.$game_id);
        if($voucher !== false && $force === false)
            return $voucher;

        $vouchers = new Vouchers();
        $data = \Yii::app()->curl->get(self::$voucher_link.$game_id);
        if($data === false || !($data = \CJSON::decode($data)))
            return $vouchers;

        foreach ($data as $v)
            $vouchers->addVoucher($v);

        Yii::app()->cache->set('voucher_'.$game_id, $voucher, 3);
        return $vouchers;
    }

    /**
     * @param $ids
     * @param $game_id
     * @return bool
     *
     * @deprecated
     */
    public static function takeVouchers($ids, $game_id)
    {
        $data = [
            'key' => 'I9RdXHeFYNlufui3TrRZ38U8',
            'owner' => $game_id,
            'vid' => $ids
        ];
        $data = \Yii::app()->curl->post(self::$voucher_link_pay, $data);
        if($data === false || !($data = \CJSON::decode($data)))
            return false;

        return isset($data['answ']) && $data['answ'] == 'true' ? true : false;
    }

    private static $_loginUrl = null;
    public static function login($uid, $hash)
    {
        $result = Yii::app()->curl->post(self::$_loginUrl, [
            'uid' => $uid,
            'hash' => $hash,
        ]);
        if($result === false)
            return false;
        $info = CJSON::decode($result);
        if(isset($info['answ']))
            return false;

        return [];
    }

    public function validateRequest($referrer)
    {
        return preg_match('/\.oldbk\.com/ui', $referrer);
    }

    public function checkIsAuth($uid, $hash)
    {
        $result = Yii::app()->curl->post($this->auth_link, [
            'uid' => $uid,
            'hash' => $hash,
        ]);
        if($result === false)
            return false;
        $info = CJSON::decode($result);
        if(isset($info['answ']) && $info['answ'] == false)
            return false;

        return true;
    }

    /**
     * @param null $game_id
     * @return bool|User
     */
    public function checkUser($game_id = null)
    {
        $this->beforeAction();

        $curl = Yii::app()->curl;

        $result = $curl->post($this->info_link, [
            'game_id' => $game_id,
            'key' => $this->api_key
        ]);

        if($result === false)
            return false;

        $info = CJSON::decode($result);
        if(isset($info['answ']) || !isset($info['id']) || !isset($info['login']) || (isset($info['login']) && trim($info['login']) == ''))
            return false;

        return new User([
            'game_id' => (int)$info['id'],
            'align' => $info['align'],
            'klan' => iconv('windows-1251', 'utf-8', urldecode($info['klan'])),
            'login' => iconv('windows-1251', 'utf-8', urldecode($info['login'])),
            'level' => (int)$info['level'],
            'block' => (int)$info['block'],
        ]);
    }

    /**
     * @param $game_id
     * @return Bank[]|bool
     */
    public function getBankInfo($game_id)
    {
        $this->beforeAction();

        $curl = Yii::app()->curl;

        $result = $curl->get($this->bank_info_link, [
            'owner' => $game_id,
            'key' => $this->api_key
        ]);

        if($result === false)
            return false;

        $info = CJSON::decode($result);
        if(isset($info['answ']))
            return false;

        $returned = [];
        foreach ($info as $item)
            $returned[] = new Bank($item);

       return $returned;
    }

    /**
     * @param $game_id
     * @return Money[]|bool
     */
    public function getMoney($game_id)
    {
        $this->beforeAction();

        $curl = Yii::app()->curl;

        $result = $curl->get($this->money_info_link, [
            'owner' => $game_id,
            'key' => $this->api_key
        ]);

        if($result === false)
            return false;

        $info = CJSON::decode($result);
        if(isset($info['answ']))
            return false;

        $returned = [];
        foreach ($info as $item)
            $returned[] = new Money($item);

        return $returned;
    }

	/**
	 * @param $game_id
	 * @return Money[]|bool
	 */
	public function getGold($game_id)
	{
		$this->beforeAction();

		$curl = Yii::app()->curl;

		$result = $curl->get($this->gold_info_link, [
			'owner' => $game_id,
			'key' => $this->api_key
		]);

		if($result === false)
			return false;

		$info = CJSON::decode($result);
		if(isset($info['answ']))
			return false;

		$returned = [];
		foreach ($info as $item)
			$returned[] = new Money($item);

		return $returned;
	}

    /**
     * @param $game_id
     * @param $bank_id
     * @param $pass
     * @return bool
     */
    public function bankAuth($game_id, $bank_id, $pass)
    {
        $this->beforeAction();

        $curl = Yii::app()->curl;

        $result = $curl->get($this->bank_auth_link, [
            'owner' => $game_id,
            'bankid' => $bank_id,
            'pass' => iconv('utf8', 'windows-1251', $pass),
            'key' => $this->api_key
        ]);

        if($result === false)
            return false;

        $info = CJSON::decode($result);
        if(isset($info['answ']))
            return false;

        return true;
    }

    public function canTakeKr($game_id, $price)
    {
        $returned = false;

        $MoneyList = $this->getMoney($game_id);
        foreach ($MoneyList as $Money) {
            if($Money->getMoney() >= $price) {
                $returned = true;
                break;
            }

        }

        return $returned;
    }

    /**
     * @param $game_id
     * @param $price
     * @return bool
     */
    public function takeKr($game_id, $price)
    {
        $this->beforeAction();

        $curl = Yii::app()->curl;

        $result = $curl->get($this->cr_operation_link, [
            'owner' => $game_id,
            'getkr' => $price,
            'key' => $this->api_key
        ]);

        if($result === false)
            return false;

        $info = CJSON::decode($result);
        if(isset($info['answ']) && $info['answ'] == true)
            return true;

        return false;
    }

    /**
     * @param $game_id
     * @param $price
     * @return bool
     */
    public function putKr($game_id, $price)
    {
        $this->beforeAction();

        $curl = Yii::app()->curl;
        $params = [
            'owner' => $game_id,
            'putkr' => $price,
            'key' => $this->api_key
        ];

        $result = $curl->get($this->cr_operation_link, $params);

        if($result === false)
            return false;

        $info = CJSON::decode($result);
        if(isset($info['answ']) && $info['answ'] == true)
            return true;

        return false;
    }

    public function canTakeEkr($game_id, $price, $bankid)
    {
        $returned = false;

        $BankList = $this->getBankInfo($game_id);
        foreach ($BankList as $Bank) {
            if($Bank->getId() != $bankid) continue;

            if($Bank->getEkr() >= $price) {
                $returned = true;
                break;
            }

        }

        return $returned;
    }

    /**
     * @param $game_id
     * @param $price
     * @param $bankid
     * @return bool
     */
    public function takeEkr($game_id, $price, $bankid)
    {
        $this->beforeAction();

        $curl = Yii::app()->curl;

        $result = $curl->get($this->ekr_operation_link, [
            'owner' => $game_id,
            'getekr' => $price,
            'bankid' => $bankid,
            'key' => $this->api_key
        ]);

        if($result === false)
            return false;

        $info = CJSON::decode($result);
        if(isset($info['answ']) && $info['answ'] == true)
            return true;

        return false;
    }

    /**
     * @param $game_id
     * @param $price
     * @param $bankid
     * @return bool
     */
    public function putEkr($game_id, $price, $bankid)
    {
        $this->beforeAction();

        $curl = Yii::app()->curl;

        $result = $curl->get($this->ekr_operation_link, [
            'owner' => $game_id,
            'putekr' => $price,
            'bankid' => $bankid,
            'key' => $this->api_key
        ]);

        if($result === false)
            return false;

        $info = CJSON::decode($result);
        if(isset($info['answ']) && $info['answ'] == true)
            return true;

        return false;
    }

	public function canTakeGold($game_id, $price)
	{
		$returned = false;

		$MoneyList = $this->getGold($game_id);
		foreach ($MoneyList as $Money) {
			if($Money->getMoney() >= $price) {
				$returned = true;
				break;
			}

		}

		return $returned;
	}

	/**
	 * @param $game_id
	 * @param $price
	 * @return bool
	 */
	public function takeGold($game_id, $price)
	{
		$this->beforeAction();

		$curl = Yii::app()->curl;

		$result = $curl->get($this->gold_operation_link, [
			'owner' => $game_id,
			'getgold' => $price,
			'key' => $this->api_key
		]);

		if($result === false)
			return false;

		$info = CJSON::decode($result);
		if(isset($info['answ']) && $info['answ'] == true)
			return true;

		return false;
	}

	/**
	 * @param $game_id
	 * @param $price
	 * @return bool
	 */
	public function putGold($game_id, $price)
	{
		$this->beforeAction();

		$curl = Yii::app()->curl;
		$params = [
			'owner' => $game_id,
			'putgold' => $price,
			'key' => $this->api_key
		];

		$result = $curl->get($this->gold_operation_link, $params);

		if($result === false)
			return false;

		$info = CJSON::decode($result);
		if(isset($info['answ']) && $info['answ'] == true)
			return true;

		return false;
	}
} 