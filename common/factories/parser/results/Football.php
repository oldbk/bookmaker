<?php
namespace common\factories\parser\results;
/**
 * Created by PhpStorm.
 */
class Football extends Base
{
    public function getData()
    {
        $FootballResult = new \common\sport\result\Football();
        if(($info = $this->getEvent()) == false) {
            return $FootballResult;
        }

        $result = $info['result'];
        if(preg_match('/не состоялся|не состоялся|матч отменен/ui', $result)) {
            $FootballResult
                ->setIsEmpty(false)
                ->setIsCancel(true);
            return $FootballResult;
        }

        if(preg_match('/(\d+):(\d+).+?(\d+):(\d+)/ui', $result, $out)) {
            $FootballResult
                ->setIsEmpty(false)
                ->setAttribute('team_1_part_1', (int)$out[3])
                ->setAttribute('team_2_part_1', (int)$out[4])
                ->setAttribute('team_1_part_2', (int)($out[1] - $out[3]))
                ->setAttribute('team_2_part_2', (int)($out[2] - $out[4]))
                ->setTeam1Result((int)$out[1])
                ->setTeam2Result((int)$out[2]);
            return $FootballResult;
        }

        if(preg_match('/(\d+):(\d+)/ui', $result, $out)) {
            $FootballResult
                ->setIsEmpty(false)
                ->setTeam1Result((int)$out[1])
                ->setTeam2Result((int)$out[2]);
            return $FootballResult;
        }

        return $FootballResult;
    }

    protected function prepareTeams()
    {
        $returned = parent::prepareTeams();

        foreach ($returned as $team) {
            if(preg_match('/угл\./ui', $team)) {
                $returned[] = trim(str_replace('угл.', 'угловые', $team));
            }

            if(preg_match('/ж\/к/ui', $team)) {
                $returned[] = trim(str_replace('ж/к', 'желтые карточки', $team));
            }
        }

        $this->setTeamList($returned);

        return $returned;
    }
}