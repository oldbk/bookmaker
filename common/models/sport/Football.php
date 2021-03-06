<?php

use \common\helpers\SportHelper;
class Football extends Sport
{
	/**
	 * @param string $className
	 * @return Football
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function defaultScope()
	{
		$t = $this->getTableAlias(false, false);
		return [
			'condition' => $t.'.sport_type = :'.$t.'sport_type',
			'params' => [':'.$t.'sport_type' => SportHelper::SPORT_FOOTBALL_ID]
		];
	}

	public function beforeValidate()
	{
		$r = parent::beforeValidate();
		$this->sport_type = SportHelper::SPORT_FOOTBALL_ID;

		return $r;
	}
}