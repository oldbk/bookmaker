<?php

use common\interfaces\iStatus;
use common\helpers\SportHelper;
/**
 * Created by PhpStorm.
 * @method \common\sport\ratio\Football getNewRatio()
 * @method \common\sport\ratio\Football getOldRatio()
 * @method \common\sport\result\Football getResult()
 * @method \FootballEvent copy()
 */
class FootballEvent extends SportEvent implements iSportEvent
{
    /**
     * @param string $className
     * @return FootballEvent
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    public function init()
    {
        parent::init();

        $this
            ->setOldRatio(new \common\sport\ratio\Football())
            ->setNewRatio(new \common\sport\ratio\Football())
            ->setResult(new \common\sport\result\Football());
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

    /**
     * @return bool
     */
    public function canAuto()
    {
        if(parent::canAuto() === false)
            return false;

        $regex = '/офсайд|угл\.|ж\/к/ui';
        if(preg_match($regex, $this->getTeam1(), $out) || preg_match($regex, $this->getTeam2(), $out)) {
            $this->setNotAuto(true)
                ->setNotAutoReason('Офсайд, угл. или ж/к');

            return false;
        }

        return true;
    }

    public function upDown($field)
    {
        $last_value = $this->getOldRatio()->getAttribute($field);
        $new_value = $this->getNewRatio()->getAttribute($field);
        if($last_value === null || $new_value === null || $new_value == $last_value)
            return null;
        switch (true) {
            case ($new_value > $last_value): //поднялся
                $diff = $new_value - $last_value;
                return '<span class="glyphicon glyphicon-arrow-up ratio-up green" title="+'.$diff.'" data-toggle="tooltip"></span>';
                break;
            case ($new_value < $last_value): //опустился
                $diff = $last_value - $new_value;
                return '<span class="glyphicon glyphicon-arrow-down ratio-down red" title="-'.$diff.'" data-toggle="tooltip"></span>';
                break;
            default:
                return null;
                break;
        }
    }

    public function getViewFile()
    {
        return $this->getEventTemplate();
    }

    /**
     * @var array
     *
     * шифруем параметры для ставок
     */
    private $_mapping = [
        'fora_ratio_1' 	=> 'fr1',
        'fora_ratio_2' 	=> 'fr2',
        'total_more' 	=> 'tm',
        'total_less' 	=> 'tl',
        'ratio_p1' 		=> 'p1',
        'ratio_x' 		=> 'x',
        'ratio_p2' 		=> 'p2',
        'ratio_1x' 		=> '1x',
        'ratio_12' 		=> '12',
        'ratio_x2' 		=> 'x2',
        'itotal_more_1' => 'itm1',
        'itotal_more_2' => 'itm2',
        'itotal_less_1' => 'itl1',
        'itotal_less_2' => 'itl2',
    ];

    /**
     * @param $field
     * @return null
     */
    public function getFieldAlias($field)
    {
        return isset($this->_mapping[$field]) ? $this->_mapping[$field] : null;
    }

    /**
     * @param $alias
     * @return null
     */
    public function getFieldByAlias($alias)
    {
        return ($field = array_search($alias, $this->_mapping)) ? $field : null;
    }

    /**
     * @return string
     * @deprecated
     */
    public function getViewFactoryTitle()
    {
        return 'football';
    }

    public function haveDiff()
    {
        if($this->getOldRatio()->isEmpty() === false)
            return $this->getNewRatio()->getAttributes() != $this->getOldRatio()->getAttributes();

        return false;
    }

    public function getMapping()
    {
        return $this->_mapping;
    }
}