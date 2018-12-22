<?php
/**
 * Created by PhpStorm.
 */
namespace common\factories\problem_event\_default;

use common\factories\problem_event\_default\_base\BaseEventProblem;
use common\factories\problem_event\_default\_interface\iProblemEvent;
use common\factories\problem_event\_default\_interface\iProblemFora;
use SportEventProblem;

class BaseFora extends BaseEventProblem implements iProblemEvent
{
    public function getProblemType()
    {
        return SportEventProblem::PROBLEM_FORA;
    }

    public function checkFieldList()
    {
        return ['fora_val_1', 'fora_val_2'];
    }

    public function hasProblem()
    {
        /** @var iProblemFora $NewRatio */
        $NewRatio = $this->getSportEvent()->getNewRatio();

        $fora_val_1 = $NewRatio->getForaVal1();
        $fora_val_2 = $NewRatio->getForaVal2();
        $foraVal1 = $fora_val_1 < 0 ? (-1) * $fora_val_1 : $fora_val_1;
        $foraVal2 = $fora_val_2 < 0 ? (-1) * $fora_val_2 : $fora_val_2;

        return $foraVal1 != $foraVal2 || ($fora_val_1 > 0 && $fora_val_2 > 0);
    }

    public function checkSameProblem($problems)
    {
        /** @var iProblemFora $NewRatio */
        $NewRatio = $this->getSportEvent()->getNewRatio();

        $fora_val_1 = $NewRatio->getForaVal1();
        $fora_val_2 = $NewRatio->getForaVal2();

        if(!is_array($problems))
            $problems = [$problems];

        /** @var SportEventProblem $problem */
        foreach ($problems as $problem) {
            $custom = unserialize($problem->getCustom());
            if($custom['fora_1'] == $fora_val_1
                && $custom['fora_2'] == $fora_val_2)

                return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function checkProblem()
    {
        $isProblem = $this->hasProblem();

        $this->setIsProblem($isProblem);
        if($isProblem && !$this->getProblemEvent()) {
            /** @var iProblemFora $NewRatio */
            $NewRatio = $this->getSportEvent()->getNewRatio();

            $fora_val_1 = $NewRatio->getForaVal1();
            $fora_val_2 = $NewRatio->getForaVal2();

            $msg = sprintf('Проблемы со значением фор. Фора 1: %s Фора 2: %s', $fora_val_1, $fora_val_2);
            $params = [
                'fora_1' => $fora_val_1,
                'fora_2' => $fora_val_2
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