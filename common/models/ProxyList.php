<?php

Yii::import('common.models._base.BaseProxyList');

/**
 * Class ProxyList
 *
 * @property int attemt
 * @property int proxy_source
 * @property int in_process
 */
class ProxyList extends BaseProxyList
{
    /**
     * @param string $className
     * @return ProxyList
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
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     * @return $this
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param int $port
     * @return $this
     */
    public function setPort($port)
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @return int
     */
    public function getDelay()
    {
        return $this->delay;
    }

    /**
     * @param int $delay
     * @return $this
     */
    public function setDelay($delay)
    {
        $this->delay = $delay;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->country_code;
    }

    /**
     * @param string $country_code
     * @return $this
     */
    public function setCountryCode($country_code)
    {
        $this->country_code = $country_code;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountryName()
    {
        return $this->country_name;
    }

    /**
     * @param string $country_name
     * @return $this
     */
    public function setCountryName($country_name)
    {
        $this->country_name = $country_name;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isIsEnable()
    {
        return $this->is_enable;
    }

    /**
     * @param boolean $is_enable
     * @return $this
     */
    public function setIsEnable($is_enable)
    {
        $this->is_enable = $is_enable;
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
    public function getAttemt()
    {
        return $this->attemt;
    }

    /**
     * @param int $attemt
     * @return $this
     */
    public function setAttemt($attemt)
    {
        $this->attemt = $attemt;
        return $this;
    }

    public function addAttemt($count = 1)
    {
        $this->attemt += $count;
    }

    /**
     * @return int
     */
    public function getProxySource()
    {
        return $this->proxy_source;
    }

    /**
     * @param int $proxy_source
     *
     * @return $this
     */
    public function setProxySource($proxy_source)
    {
        $this->proxy_source = $proxy_source;
        return $this;
    }

    /**
     * @return int
     */
    public function getInProcess()
    {
        return $this->in_process;
    }

    /**
     * @param int $in_process
     *
     * @return $this
     */
    public function setInProcess($in_process)
    {
        $this->in_process = $in_process;
        return $this;
    }
}