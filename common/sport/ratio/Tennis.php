<?php
namespace common\sport\ratio;
use common\factories\problem_event\_default\_interface\iProblemFora;
use common\factories\problem_event\_default\_interface\iProblemForaWin;
use common\factories\ratio\_default\_base\BaseFora;
use common\factories\ratio\_default\_base\BaseTotal;
use common\factories\RatioFactory;
use common\helpers\SportHelper;
use common\sport\ratio\_interfaces\iRatio;
use common\sport\ratio\_interfaces\iRatioHomepage;

/**
 * Created by PhpStorm.
 */
class Tennis extends Base implements iRatio, iRatioHomepage, iProblemForaWin, iProblemFora
{
    /** @var float */
    protected $ratio_20;
    /** @var float */
    protected $ratio_21;
    /** @var float */
    protected $ratio_12;
    /** @var float */
    protected $ratio_02;
    /** @var float */
    protected $ratio_plus15_1;
    /** @var float */
    protected $ratio_plus15_2;

    /**
     * @return float
     */
    public function getRatio20()
    {
        return $this->ratio_20;
    }

    /**
     * @param float $ratio_20
     * @return $this
     */
    public function setRatio20($ratio_20)
    {
        $this->ratio_20 = $ratio_20;
        return $this;
    }

    /**
     * @return float
     */
    public function getRatio21()
    {
        return $this->ratio_21;
    }

    /**
     * @param float $ratio_21
     * @return $this
     */
    public function setRatio21($ratio_21)
    {
        $this->ratio_21 = $ratio_21;
        return $this;
    }

    /**
     * @return float
     */
    public function getRatio12()
    {
        return $this->ratio_12;
    }

    /**
     * @param float $ratio_12
     * @return $this
     */
    public function setRatio12($ratio_12)
    {
        $this->ratio_12 = $ratio_12;
        return $this;
    }

    /**
     * @return float
     */
    public function getRatio02()
    {
        return $this->ratio_02;
    }

    /**
     * @param float $ratio_02
     * @return $this
     */
    public function setRatio02($ratio_02)
    {
        $this->ratio_02 = $ratio_02;
        return $this;
    }

    /**
     * @return float
     */
    public function getRatioPlus152()
    {
        return $this->ratio_plus15_2;
    }

    /**
     * @param float $ratio_plus15_2
     * @return $this
     */
    public function setRatioPlus152($ratio_plus15_2)
    {
        $this->ratio_plus15_2 = $ratio_plus15_2;
        return $this;
    }

    /**
     * @return float
     */
    public function getRatioPlus151()
    {
        return $this->ratio_plus15_1;
    }

    /**
     * @param float $ratio_plus15_1
     * @return $this
     */
    public function setRatioPlus151($ratio_plus15_1)
    {
        $this->ratio_plus15_1 = $ratio_plus15_1;
        return $this;
    }

    protected $auto_require_fields = [
        'fora_val_1'    => 0,
        'fora_val_2'    => 0,
        'fora_ratio_1'  => 1.01,
        'fora_ratio_2'  => 1.01,
        'total_val'     => 0,
        'total_more'    => 1.01,
        'total_less'    => 1.01,
        'ratio_p1'      => 1.01,
        'ratio_p2'      => 1.01,
        'ratio_20'      => 1.01,
        'ratio_21'      => 1.01,
        'ratio_12'      => 1.01,
        'ratio_02'      => 1.01,
        'ratio_plus15_1'    => 1.01,
        'ratio_plus15_2'    => 1.01,
    ];

    /**
     * @return array
     */
    public function getAutoRequireFields()
    {
        return $this->auto_require_fields;
    }

    public function getAttributes()
    {
        return [
            'fora_val_1'        => $this->getForaVal1(),
            'fora_val_2'        => $this->getForaVal2(),
            'fora_ratio_1'      => $this->getForaRatio1(),
            'fora_ratio_2'      => $this->getForaRatio2(),
            'total_val'         => $this->getTotalVal(),
            'total_more'        => $this->getTotalMore(),
            'total_less'        => $this->getTotalLess(),
            'ratio_p1'          => $this->getRatioP1(),
            'ratio_p2'          => $this->getRatioP2(),
            'ratio_20'          => $this->getRatio20(),
            'ratio_21'          => $this->getRatio21(),
            'ratio_12'          => $this->getRatio12(),
            'ratio_02'          => $this->getRatio02(),
            'ratio_plus15_1'    => $this->getRatioPlus151(),
            'ratio_plus15_2'    => $this->getRatioPlus152(),
            'itotal_val_1'      => $this->getItotalVal1(),
            'itotal_val_2'      => $this->getItotalVal2(),
            'itotal_more_1'     => $this->getItotalMore1(),
            'itotal_more_2'     => $this->getItotalMore2(),
            'itotal_less_1'     => $this->getItotalLess1(),
            'itotal_less_2'     => $this->getItotalLess2(),
        ];
    }

    protected function getRatioList()
    {
        return [
            'fora_ratio_1',
            'fora_ratio_2',
            'total_more',
            'total_less',
            'ratio_p1',
            'ratio_p2',
            'ratio_20',
            'ratio_21',
            'ratio_12',
            'ratio_02',
            'ratio_plus15_1',
            'ratio_plus15_2',
            'itotal_more_1',
            'itotal_more_2',
            'itotal_less_1',
            'itotal_less_2',
        ];
    }

    public function isTotalHint()
    {
        if($this->getTotalVal() == null)
            return false;

        /** @var BaseTotal $RatioFactory */
        $RatioFactory = RatioFactory::factory(SportHelper::SPORT_TENNIS_ID, 'total_more');
        return $RatioFactory
            ->setTotalVal($this->getTotalVal())
            ->isGandikap();
    }

    public function isFora1Hint()
    {
        if($this->getForaVal1() == null)
            return false;

        /** @var BaseFora $RatioFactory */
        $RatioFactory = RatioFactory::factory(SportHelper::SPORT_TENNIS_ID, 'fora_ratio_1');
        return $RatioFactory->setFora($this->getForaVal1())
            ->isGandikap();
    }

    public function isFora2Hint()
    {
        if($this->getForaVal2() == null)
            return false;

        /** @var BaseFora $RatioFactory */
        $RatioFactory = RatioFactory::factory(SportHelper::SPORT_TENNIS_ID, 'fora_ratio_2');
        return $RatioFactory->setFora($this->getForaVal2())
            ->isGandikap();
    }
}