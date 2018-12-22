<?php
/**
 * Created by PhpStorm.
 */

namespace common\sport\result;


class Football extends Base implements iResult
{
    protected $team_1_part_1;
    protected $team_1_part_2;

    protected $team_2_part_1;
    protected $team_2_part_2;

    /**
     * @return mixed
     */
    public function getTeam1Part1()
    {
        return $this->team_1_part_1;
    }

    /**
     * @param mixed $team_1_part_1
     * @return $this
     */
    public function setTeam1Part1($team_1_part_1)
    {
        $this->team_1_part_1 = $team_1_part_1;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTeam1Part2()
    {
        return $this->team_1_part_2;
    }

    /**
     * @param mixed $team_1_part_2
     * @return $this
     */
    public function setTeam1Part2($team_1_part_2)
    {
        $this->team_1_part_2 = $team_1_part_2;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTeam2Part1()
    {
        return $this->team_2_part_1;
    }

    /**
     * @param mixed $team_2_part_1
     * @return $this
     */
    public function setTeam2Part1($team_2_part_1)
    {
        $this->team_2_part_1 = $team_2_part_1;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTeam2Part2()
    {
        return $this->team_2_part_2;
    }

    /**
     * @param mixed $team_2_part_2
     * @return $this
     */
    public function setTeam2Part2($team_2_part_2)
    {
        $this->team_2_part_2 = $team_2_part_2;
        return $this;
    }

    protected function getDuringResult()
    {
        return [
            'team_1_part_1' => $this->getTeam1Part1(),
            'team_1_part_2' => $this->getTeam1Part2(),

            'team_2_part_1' => $this->getTeam2Part1(),
            'team_2_part_2' => $this->getTeam2Part2(),
        ];
    }

    protected function getDuringResultByTeam()
    {
        return [
            [$this->getTeam1Part1(), $this->getTeam2Part1()],
            [$this->getTeam1Part2(), $this->getTeam2Part2()],
        ];
    }

    public function getResultString()
    {
        if($this->isCancel())
            return 'матч отменен/не состоялся';

        if($this->getTeam1Result() === null || $this->getTeam2Result() === null)
            return null;

        return sprintf('%s:%s (%s:%s)', $this->getTeam1Result(), $this->getTeam2Result(), $this->getTeam1Part1(), $this->getTeam2Part1());
    }
}