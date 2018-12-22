<?php

Yii::import('common.models._base.BasePriceSettings');

/**
 * Class PriceSettings
 *
 * @property object onAfterChange
 * @property string max_ratio
 */
class PriceSettings extends BasePriceSettings implements \common\interfaces\iAdminLog
{
    /**
     * @param string $className
     * @return PriceSettings
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

    public function rules() {
        return [
            ['price_id', 'required'],
            ['price_id, auto_output, update_at, create_at', 'numerical', 'integerOnly'=>true],
            ['max_ratio, dop_ratio, event_limit, strange_output, min_bet', 'length', 'max'=>19],
            ['short_name', 'length', 'max'=>50],
            ['max_ratio, dop_ratio, event_limit, strange_output, auto_output, min_bet, short_name, update_at, create_at', 'default', 'setOnEmpty' => true, 'value' => null],
            ['max_ratio, price_id, dop_ratio, event_limit, strange_output, auto_output, min_bet, short_name, update_at, create_at', 'safe', 'on'=>'search'],
        ];
    }

    public function attributeLabels() {
        return [
            'price_id' => Yii::t('app', 'Price'),
            'dop_ratio' => Yii::t('app', 'Доп. ратио'),
            'event_limit' => Yii::t('app', 'Лимит для события'),
            'strange_output' => Yii::t('app', 'Странный вывод'),
            'auto_output' => Yii::t('app', 'Авто вывод'),
            'min_bet' => Yii::t('app', 'Минимальная ставка'),
            'short_name' => Yii::t('app', 'Краткое название'),
            'update_at' => Yii::t('app', 'Update At'),
            'create_at' => Yii::t('app', 'Create At'),
            'max_ratio' => Yii::t('app', 'Макс. коэф.')
        ];
    }

    public function getId()
    {
        return $this->getPriceId();
    }

    public function afterFind()
    {
        parent::afterFind();

        foreach ($this->getCompareList() as $field)
            $this->_old_attributes[$field] = $this->getAttribute($field);
    }

    private $_old_attributes = [];
    public function getOldAttributes()
    {
        return $this->_old_attributes;
    }

    public function getNewAttributes()
    {
        $return = [];
        foreach ($this->getCompareList() as $field)
            $return[$field] = $this->getAttribute($field);

        return $return;
    }

    public function getCompareList()
    {
        return [
            'dop_ratio',
            'event_limit',
            'strange_output',
            'auto_output',
            'min_bet',
            'short_name'
        ];
    }

    /**
     * @return int
     */
    public function getPriceId()
    {
        return $this->price_id;
    }

    /**
     * @param int $price_id
     * @return $this
     */
    public function setPriceId($price_id)
    {
        $this->price_id = $price_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getDopRatio()
    {
        return $this->dop_ratio;
    }

    /**
     * @param string $dop_ratio
     * @return $this
     */
    public function setDopRatio($dop_ratio)
    {
        $this->dop_ratio = $dop_ratio;
        return $this;
    }

    /**
     * @return string
     */
    public function getEventLimit()
    {
        return $this->event_limit;
    }

    /**
     * @param string $event_limit
     * @return $this
     */
    public function setEventLimit($event_limit)
    {
        $this->event_limit = $event_limit;
        return $this;
    }

    /**
     * @return string
     */
    public function getStrangeOutput()
    {
        return $this->strange_output;
    }

    /**
     * @param string $strange_output
     * @return $this
     */
    public function setStrangeOutput($strange_output)
    {
        $this->strange_output = $strange_output;
        return $this;
    }

    /**
     * @return int
     */
    public function isAutoOutput()
    {
        return $this->auto_output;
    }

    /**
     * @param int $auto_output
     * @return $this
     */
    public function setAutoOutput($auto_output)
    {
        $this->auto_output = $auto_output;
        return $this;
    }

    /**
     * @return string
     */
    public function getMinBet()
    {
        return $this->min_bet;
    }

    /**
     * @param string $min_bet
     * @return $this
     */
    public function setMinBet($min_bet)
    {
        $this->min_bet = $min_bet;
        return $this;
    }

    /**
     * @return string
     */
    public function getShortName()
    {
        return $this->short_name;
    }

    /**
     * @param string $short_name
     * @return $this
     */
    public function setShortName($short_name)
    {
        $this->short_name = $short_name;
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
     * @return string
     */
    public function getMaxRatio()
    {
        return $this->max_ratio;
    }

    /**
     * @param string $max_ratio
     * @return $this
     */
    public function setMaxRatio($max_ratio)
    {
        $this->max_ratio = $max_ratio;
        return $this;
    }

    public function onAfterChange($event)
    {
        $this->raiseEvent('onAfterChange', $event);
    }

    public function updateAction()
    {
        $r = $this->save();

        if($r) {
            if($this->hasEvent('onAfterChange'))
                $this->onAfterChange(new \CEvent($this));
        }

        return $r;
    }
}