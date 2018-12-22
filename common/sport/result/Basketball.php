<?php
/**
 * Created by PhpStorm.
 */

namespace common\sport\result;


class Basketball extends Base implements iResult
{
    protected $team_1_part_1;
    protected $team_1_part_2;
    protected $team_1_part_3;
    protected $team_1_part_4;

    protected $team_2_part_1;
    protected $team_2_part_2;
    protected $team_2_part_3;
    protected $team_2_part_4;

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
    public function getTeam1Part3()
    {
        return $this->team_1_part_3;
    }

    /**
     * @param mixed $team_1_part_3
     * @return $this
     */
    public function setTeam1Part3($team_1_part_3)
    {
        $this->team_1_part_3 = $team_1_part_3;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTeam1Part4()
    {
        return $this->team_1_part_4;
    }

    /**
     * @param mixed $team_1_part_4
     * @return $this
     */
    public function setTeam1Part4($team_1_part_4)
    {
        $this->team_1_part_4 = $team_1_part_4;
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

    /**
     * @return mixed
     */
    public function getTeam2Part3()
    {
        return $this->team_2_part_3;
    }

    /**
     * @param mixed $team_2_part_3
     * @return $this
     */
    public function setTeam2Part3($team_2_part_3)
    {
        $this->team_2_part_3 = $team_2_part_3;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTeam2Part4()
    {
        return $this->team_2_part_4;
    }

    /**
     * @param mixed $team_2_part_4
     * @return $this
     */
    public function setTeam2Part4($team_2_part_4)
    {
        $this->team_2_part_4 = $team_2_part_4;
        return $this;
    }

    protected function getDuringResult()
    {
        return [
            'team_1_part_1' => $this->getTeam1Part1(),
            'team_1_part_2' => $this->getTeam1Part2(),
            'team_1_part_3' => $this->getTeam1Part3(),
            'team_1_part_4' => $this->getTeam1Part4(),

            'team_2_part_1' => $this->getTeam2Part1(),
            'team_2_part_2' => $this->getTeam2Part2(),
            'team_2_part_3' => $this->getTeam2Part3(),
            'team_2_part_4' => $this->getTeam2Part4(),
        ];
    }

    protected function getDuringResultByTeam()
    {
        return [
            [$this->getTeam1Part1(), $this->getTeam2Part1()],
            [$this->getTeam1Part2(), $this->getTeam2Part2()],
            [$this->getTeam1Part3(), $this->getTeam2Part3()],
            [$this->getTeam1Part4(), $this->getTeam2Part4()],
        ];
    }
}