<?php
namespace common\factories\parser\results;
use common\factories\parser\results\_interfaces\iResult;
use phpQuery;
/**
 * Created by PhpStorm.
 */
class Hokkey extends Base implements iResult
{
    public function getData()
    {
        $Result = new \common\sport\result\Hokkey();
        if(($info = $this->getEvent()) == false) {
            return $Result;
        }

        $result = $info['result'];
        if(preg_match('/не состоялся|не состоялся|матч отменен/ui', $result)) {
            $Result->setIsCancel(true)
                ->setIsEmpty(false);
            return $Result;
        }

        if(preg_match('/^(\d+):(\d+)/ui', $result, $out)) {
            $result_1 = 0;
            $result_2 = 0;

            if(preg_match('/\((.+?)\)/ui', $result, $out2)) {
                $set_list = explode(',', $out2[1]);

                foreach ($set_list as $key => $res) {
                    if(preg_match('/(\d+):(\d+)/ui', $res, $_out)) {
                        $Result->setAttribute(sprintf('team_1_part_%d', $key + 1), (int)$_out[1]);
                        $Result->setAttribute(sprintf('team_2_part_%d', $key + 1), (int)$_out[2]);

                        $result_1 += (int)$_out[1];
                        $result_2 += (int)$_out[2];
                    }
                }
            }

            if($result_1 == 0 || $result_2 == 0) {
                $result_1 = (int)$out[1];
                $result_2 = (int)$out[2];
            }

            $Result
                ->setIsEmpty(false)
                ->setTeam1Result($result_1)
                ->setTeam2Result($result_2);
        }

        return $Result;
    }

    protected function prepareTeams()
    {
        $returned = parent::prepareTeams();

        foreach ($returned as $team) {
            $returned[] = str_ireplace(
                ['бр.в створ', 'штр.время'],
                ['броски в створ', 'штраф. время'],
                $team);
        }

        $this->setTeamList($returned);

        return $returned;
    }
}