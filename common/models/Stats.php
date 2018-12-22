<?php

Yii::import('common.models._base.BaseStats');

/**
 * Class Stats
 *
 * @property int last_update
 * @property int stats_at
 */
class Stats extends BaseStats
{
    /**
     * @param string $className
     * @return Stats
     */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

    public function behaviors()
    {
        return [
            // Password behavior strategy
            'MTimestampBehavior' => [
                'class' => 'common\extensions\behaviors\MTimestampBehavior',
                'createAttribute' => 'create_at',
                'updateAttribute' => 'update_at',
                'setUpdateOnCreate' => true
            ]
        ];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getDatestring()
    {
        return $this->datestring;
    }

    /**
     * @param string $datestring
     * @return $this
     */
    public function setDatestring($datestring)
    {
        $this->datestring = $datestring;
        return $this;
    }

    /**
     * @return float
     */
    public function getMoneyVoucherIn()
    {
        return $this->money_voucher_in;
    }

    /**
     * @param string $money_voucher_in
     * @return $this
     */
    public function setMoneyVoucherIn($money_voucher_in)
    {
        $this->money_voucher_in = $money_voucher_in;
        return $this;
    }

    /**
     * @param $money_voucher_in
     * @return $this
     */
    public function addMoneyVoucherIn($money_voucher_in)
    {
        $this->money_voucher_in += $money_voucher_in;
        return $this;
    }

    /**
     * @return float
     */
    public function getMoneyVoucherOut()
    {
        return $this->money_voucher_out;
    }

    /**
     * @param string $money_voucher_out
     * @return $this
     */
    public function setMoneyVoucherOut($money_voucher_out)
    {
        $this->money_voucher_out = $money_voucher_out;
        return $this;
    }

    /**
     * @param $money_voucher_out
     * @return $this
     */
    public function addMoneyVoucherOut($money_voucher_out)
    {
        $this->money_voucher_out += $money_voucher_out;
        return $this;
    }

    /**
     * @return float
     */
    public function getMoneyEkrIn()
    {
        return $this->money_ekr_in;
    }

    /**
     * @param string $money_ekr_in
     * @return $this
     */
    public function setMoneyEkrIn($money_ekr_in)
    {
        $this->money_ekr_in = $money_ekr_in;
        return $this;
    }

    /**
     * @param $money_ekr_in
     * @return $this
     */
    public function addMoneyEkrIn($money_ekr_in)
    {
        $this->money_ekr_in += $money_ekr_in;
        return $this;
    }

    /**
     * @return float
     */
    public function getMoneyEkrOut()
    {
        return $this->money_ekr_out;
    }

    /**
     * @param string $money_ekr_out
     * @return $this
     */
    public function setMoneyEkrOut($money_ekr_out)
    {
        $this->money_ekr_out = $money_ekr_out;
        return $this;
    }

    /**
     * @param $money_ekr_out
     * @return $this
     */
    public function addMoneyEkrOut($money_ekr_out)
    {
        $this->money_ekr_out += $money_ekr_out;
        return $this;
    }

    /**
     * @return float
     */
    public function getMoneyKrIn()
    {
        return $this->money_kr_in;
    }

    /**
     * @param string $money_kr_in
     * @return $this
     */
    public function setMoneyKrIn($money_kr_in)
    {
        $this->money_kr_in = $money_kr_in;
        return $this;
    }

    /**
     * @param $money_kr_in
     * @return $this
     */
    public function addMoneyKrIn($money_kr_in)
    {
        $this->money_kr_in += $money_kr_in;
        return $this;
    }

    /**
     * @return float
     */
    public function getMoneyKrOut()
    {
        return $this->money_kr_out;
    }

    /**
     * @param string $money_kr_out
     * @return $this
     */
    public function setMoneyKrOut($money_kr_out)
    {
        $this->money_kr_out = $money_kr_out;
        return $this;
    }

    /**
     * @param $money_kr_out
     * @return $this
     */
    public function addMoneyKrOut($money_kr_out)
    {
        $this->money_kr_out += $money_kr_out;
        return $this;
    }

    /**
     * @return int
     */
    public function getUpdateAt()
    {
        return $this->update_at;
    }

    /**
     * @param int $update_at
     * @return $this
     */
    public function setUpdateAt($update_at)
    {
        $this->update_at = $update_at;
        return $this;
    }

    /**
     * @return int
     */
    public function getCreateAt()
    {
        return $this->create_at;
    }

    /**
     * @param int $create_at
     * @return $this
     */
    public function setCreateAt($create_at)
    {
        $this->create_at = $create_at;
        return $this;
    }

    /**
     * @return int
     */
    public function getLastUpdate()
    {
        return $this->last_update;
    }

    /**
     * @param int $last_update
     * @return $this
     */
    public function setLastUpdate($last_update)
    {
        $this->last_update = $last_update;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatsAt()
    {
        return $this->stats_at;
    }

    /**
     * @param int $stats_at
     * @return $this
     */
    public function setStatsAt($stats_at)
    {
        $this->stats_at = $stats_at;
        return $this;
    }

	/**
	 * @return string
	 */
	public function getMoneyGoldIn()
	{
		return $this->money_gold_in;
	}

	/**
	 * @param string $money_gold_in
	 * @return $this
	 */
	public function setMoneyGoldIn($money_gold_in)
	{
		$this->money_gold_in = $money_gold_in;
		return $this;
	}

	/**
	 * @param $money_gold_in
	 * @return $this
	 */
	public function addMoneyGoldIn($money_gold_in)
	{
		$this->money_gold_in += $money_gold_in;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getMoneyGoldOut()
	{
		return $this->money_gold_out;
	}

	/**
	 * @param string $money_gold_out
	 * @return $this
	 */
	public function setMoneyGoldOut($money_gold_out)
	{
		$this->money_gold_out = $money_gold_out;
		return $this;
	}

	/**
	 * @param $money_gold_out
	 * @return $this
	 */
	public function addMoneyGoldOut($money_gold_out)
	{
		$this->money_gold_out += $money_gold_out;
		return $this;
	}
}