<?php
/**
 * Created by PhpStorm.
 */
namespace common\factories\problem_event\_default;

use common\factories\problem_event\_default\_base\BaseEventProblem;
use common\factories\problem_event\_default\_interface\iProblemEvent;
use common\factories\problem_event\_default\_interface\iProblemForaWin;
use SportEventProblem;

class BaseForaWin extends BaseEventProblem implements iProblemEvent
{
    public function getProblemType()
    {
        return SportEventProblem::PROBLEM_FORA_WIN;
    }

    private $check_field_list = [];
    public function checkFieldList()
    {
        return $this->check_field_list;
    }

    /**
     * @return array
     */
    public function getCheckFieldList()
    {
        return $this->check_field_list;
    }

    /**
     * @param array $check_field_list
     * @return $this
     */
    public function setCheckFieldList($check_field_list)
    {
        $this->check_field_list = $check_field_list;
        return $this;
    }

    public function hasProblem()
    {
        /** @var iProblemForaWin $NewRatio */
        $NewRatio = $this->getSportEvent()->getNewRatio();

        $fora_val_1 = $NewRatio->getForaVal1();
        $fora_val_2 = $NewRatio->getForaVal2();
        $fora_ratio_1 = $NewRatio->getForaRatio1();
        $fora_ratio_2 = $NewRatio->getForaRatio2();
        $p1 = $NewRatio->getRatioP1();
        $p2 = $NewRatio->getRatioP2();

        if($fora_val_1 == 0.25 || $fora_val_2 == 0.25)
            return false;

        if($fora_val_1 < 0) {
            $this->setCheckFieldList(['fora_ratio_1', 'p1']);
            return $fora_ratio_1 < $p1;
        } elseif($fora_val_2 < 0) {
            $this->setCheckFieldList(['fora_ratio_2', 'p2']);
            return $fora_ratio_2 < $p2;
        }

        return false;
    }

    public function checkSameProblem($problems)
    {
        /** @var iProblemForaWin $NewRatio */
        $NewRatio = $this->getSportEvent()->getNewRatio();

        $fora_ratio_1 = $NewRatio->getForaVal1();
        $fora_ratio_2 = $NewRatio->getForaVal2();
        $p1 = $NewRatio->getRatioP1();
        $p2 = $NewRatio->getRatioP2();

        if(!is_array($problems))
            $problems = [$problems];

        /** @var SportEventProblem $problem */
        foreach ($problems as $problem) {
            $custom = unserialize($problem->getCustom());
            $arr1 = [
                $custom['fora_ratio_1'],
                $custom['fora_ratio_2'],
                $custom['p1'],
                $custom['p2'],
            ];

            $arr2 = [
                $fora_ratio_1,
                $fora_ratio_2,
                $p1,
                $p2,
            ];
            if($arr1 == $arr2)
                return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function checkProblem()
    {
        /** @var iProblemForaWin $NewRatio */
        $NewRatio = $this->getSportEvent()->getNewRatio();

        $fora_val_1 = $NewRatio->getForaVal1();
        $fora_val_2 = $NewRatio->getForaVal2();
        $fora_ratio_1 = $NewRatio->getForaRatio1();
        $fora_ratio_2 = $NewRatio->getForaRatio2();
        $p1 = $NewRatio->getRatioP1();
        $p2 = $NewRatio->getRatioP2();

        $isProblem = $this->hasProblem();

        $this->setIsProblem($isProblem);
        if($isProblem && !$this->getProblemEvent()) {
            $msg = sprintf('Форы => (%s)-(%s). Коэф. Фор => %s-%s. П1-П2 => %s-%s',
                $fora_val_1, $fora_val_2,
                $fora_ratio_1, $fora_ratio_2,
                $p1, $p2);
            $params = [
                'fora_ratio_1' => $fora_ratio_1,
                'fora_ratio_2' => $fora_ratio_2,
                'p1' => $p1,
                'p2' => $p2,
            ];

            return $this
                ->setIsProblem(false)
                ->create($msg, $params);
        } elseif(!$isProblem && $this->getProblemEvent()) {
            return $this
                ->setIsProblem(false)
                ->resolve();
        }

        return true;
    }
}