<?php
namespace ExampleBundle\Service\Fees;

use ExampleBundle\Service\Operation;

class NaturalOutFee extends AbstractFee
{
    protected $history = [];

    public function calculateFee(Operation $operation)
    {
        $fee = $this->fetchFee($operation);

        $operationSum = $operation->getSum();
        if ($operation->getCurrency() != $fee['currency']) {
            $operationSum = $this->exchange->convert(
                $fee['currency'],
                $operation->getCurrency(),
                $operation->getSum()
            );
        }

        $weekSum = $this->getWeekTaxableSum(
            $fee['week_sum'],
            $fee['free_operations'],
            $operation->getId(),
            $operation->getDate(),
            $operationSum
        );

        $totalFee = bcmul(
            $weekSum,
            $fee['percent'],
            self::BC_SCALE
        );

        return $totalFee;
    }

    /**
     * @param string $freeFee
     * @param int $freeOperations
     * @param int $user
     * @param string $date
     * @param string $sum
     *
     * @return string
     */
    protected function getWeekTaxableSum(
        $freeFee,
        $freeOperations,
        $user,
        $date,
        $sum
    ) {
        $date = new \DateTime($date);
        $week = $date->format("W");

        if (!isset($this->history[$user])) {
            $this->history[$user] = [];
        }
        if (!isset($this->history[$user][$week])) {
            $this->history[$user][$week] = [
                'sum' => $freeFee,
                'count' => 1,
            ];
        } else {
            $this->history[$user][$week]['count']++;
        }
        if ($this->history[$user][$week]['count'] > $freeOperations) {
            return $sum;
        }
        if (bccomp($this->history[$user][$week]['sum'], '0.00') == 0) {
            return $sum;
        }

        $sum = bcsub(
            $sum,
            $this->history[$user][$week]['sum'],
            self::BC_SCALE
        );
        if (bccomp($sum, '0.00') == -1) {
            $this->history[$user][$week]['sum'] = bcmul(
                $sum,
                '-1',
                self::BC_SCALE
            );
            $sum = '0.00';
        } else {
            $this->history[$user][$week]['sum'] = '0.00';
        }

        return $sum;
    }
}
