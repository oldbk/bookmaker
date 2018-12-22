<?php
namespace common\factories\parser\results;
use common\factories\parser\results\_interfaces\iResult;
use phpQuery;
/**
 * Created by PhpStorm.
 */
class Tennis extends Base implements iResult
{
    public function getData()
    {
        $Result = new \common\sport\result\Tennis();
        if(($info = $this->getEvent()) == false) {
            return $Result;
        }

        $result = $info['result'];
        if(preg_match('/не состоялся|не состоялся|матч отменен|отказ/ui', $result)) {
            $Result->setIsCancel(true)
                ->setIsEmpty(false);
            return $Result;
        }

        if(preg_match('/^(\d+):(\d+)/ui', $result, $out) && preg_match('/\((.+?)\)/ui', $result, $out2)) {
            $set_list = explode(',', $out2[1]);

            $setSum = $out[1] + $out[2];
            if($setSum != count($set_list))
                return $Result;

            $Result
                ->setIsEmpty(false)
                ->setTeam1Result((int)$out[1])
                ->setTeam2Result((int)$out[2]);

            foreach ($set_list as $key => $res) {
                if(preg_match('/(\d+):(\d+)/ui', $res, $_out)) {
                    $Result->setAttribute(sprintf('team_1_part_%d', $key + 1), (int)$_out[1]);
                    $Result->setAttribute(sprintf('team_2_part_%d', $key + 1), (int)$_out[2]);
                }
            }
        }

        return $Result;
    }
}