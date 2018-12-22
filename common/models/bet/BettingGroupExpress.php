<?php

class BettingGroupExpress extends BettingGroup
{
	/**
	 * @param string $className
	 * @return BettingGroupExpress
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function defaultScope()
	{
		$t = $this->getTableAlias(false, false);
		return [
			'condition' => $t.'.bet_type = :'.$t.'bet_type',
			'params' => [':'.$t.'bet_type' => self::TYPE_EXPRESS]
		];
	}

	public function beforeValidate()
	{
		$r = parent::beforeValidate();
		$this->bet_type = self::TYPE_EXPRESS;

		return $r;
	}
}