<?php

namespace ExampleBundle\Service;

class WeekGateway
{
    /**
     * @var array
     */
    protected $history = [];

    /**
     * @param string $user
     * @param string $date
     * @param string $freeFee
     *
     * @return mixed
     */
    public function getUserWeekData($user, $date, $freeFee)
    {
        if (!isset($this->history[$user])) {
            $this->history[$user] = [];
        }
        $week = $this->getWeekByDate($date);

        if (!isset($this->history[$user][$week])) {
            $this->history[$user][$week] = [
                'sum' => $freeFee,
                'count' => 0,
            ];
        }

        return $this->history[$user][$week];
    }

    /**
     * @param $user
     * @param $date
     *
     * @return mixed
     */
    public function incCounter($user, $date)
    {
        $week = $this->getWeekByDate($date);
        $this->getUserWeekData($user, $date, '0.00');
        $this->history[$user][$week]['count']++;

        return $this->history[$user][$week]['count'];
    }

    /**
     * @param string $user
     * @param string $date
     * @param string $sum
     *
     * @return bool
     */
    public function updateUserWeekSum($user, $date, $sum)
    {
        $this->getUserWeekData($user, $date, '0.00');
        $week = $this->getWeekByDate($date);
        $this->history[$user][$week]['sum'] = $sum;

        return true;
    }

    /**
     * @param $date
     *
     * @return string
     */
    protected function getWeekByDate($date)
    {
        $date = new \DateTime($date);

        return $date->format("W");
    }
}
