<?php
namespace common\components\updater;
/**
 * Created by PhpStorm.
 * User: me
 * Date: 11.02.2015
 * Time: 22:04
 */

use CApplicationComponent;
use common\interfaces\iStatus;
use common\singletons\prices\Prices;
use common\singletons\Settings;
use SportEvent;
use CDbCriteria;
use Yii;
use Exception;
use UserBetting;
use common\helpers\Convert;
class Sport extends CApplicationComponent
{
    private $_settings = [];

    public function init()
    {
        parent::init();

        $this->_settings = Settings::init()->getAttributes();
    }

    /**
     * @param $price_type
     * @return string
     * @throws Exception
     */
    private function buildMaxRate($price_type)
    {
        $maxSettings = Prices::init($price_type)->getEventLimit() * $this->getLevelAddRatio();
        $extra = 1;
        /*switch(true) {
            case ($User->getActiveBalance() == iPrice::TYPE_KR):
                $extra = 5;
                break;
        }*/

        return $maxSettings * $extra;
    }

    public function getLevelAddRatio()
    {
        $diff = 8 - Yii::app()->getUser()->getLevel();
        if($diff > 0) {
            return 1 - $diff * 0.2;
        } elseif($diff < 0) {
            return 1 + $diff * (-1) * 0.2;
        }

        return 1;
    }

    public function getMaxBetSum(SportEvent $Event)
    {
        $sumAlreadyUsed = 0;

        //кр 400 * 1.4 = 560
        //екр 150 * 1.4 = 210

        $criteria = new CDbCriteria();
        $criteria->addCondition('`t`.user_id = :user_id');
        //$criteria->addCondition("DATE_FORMAT(`t`.create_at_datetime, '%d.%m.%Y') = :datetime");
        $criteria->addCondition('`t`.price_type = :price_type');
        $criteria->addCondition('`t`.event_id = :event_id');
        $criteria->addCondition('`t`.result_status != :result_status');
        $criteria->params = [
            //':datetime' => date('d.m.Y'),
            ':event_id' => $Event->getId(),
            ':user_id' => Yii::app()->getUser()->getId(),
            ':price_type' => Yii::app()->getUser()->getAB(),
            ':result_status' => iStatus::RESULT_RETURN,
        ];
        /** @var \UserBetting[] $Models */
        $Models = \UserBetting::model()->findAll($criteria);
        foreach ($Models as $Bet)
            $sumAlreadyUsed += $Bet->getRatioValue() * $Bet->getPrice();

        $maxRate = $this->buildMaxRate(Yii::app()->getUser()->getAB()) * $Event->getRatioChangeMaxPrice() * Yii::app()->getUser()->model()->getExtraRatio();
        $diff = $maxRate - $sumAlreadyUsed;
        if($this->_settings['is_daily_limit'] && $diff >= ($day_limit = Yii::app()->getUser()->getFreeDailyLimit()))
            return $day_limit;

        return $diff;
    }

    public function getMaxBet($model, $ratio_value)
    {
        if($ratio_value == 0)
            return 0;
        $max_bet_sum = $this->getMaxBetSum($model);
        if($max_bet_sum < 0) $r = 0;
        else $r = Convert::roundMaxBet(($max_bet_sum / $ratio_value));

        return Convert::getMoneyFormat($r);
    }

    /**
     * @param $ratio
     * @param $price_type
     * @param null $dop_ratio
     * @return null|string
     * @throws Exception
     */
    public function prepareRatio($ratio, $price_type = null, $dop_ratio = null)
    {
        if($ratio === null)
            return null;
        if($dop_ratio === null) {
            if($price_type === null)
                $price_type = Yii::app()->getUser()->getAB();

            $dop_ratio = Prices::init($price_type)->getDopRatio();
        }

        $r = $dop_ratio > 0 ? $ratio * $dop_ratio : $ratio;
        if($r < 1.01) $r = 1.01;

        return Convert::getFormat($r);
    }
}