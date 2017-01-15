<?php

namespace ExampleBundle\Service;

use ExampleBundle\Service\Fees\FeesConfig;

class FeeCalculator
{
    /**
     * @var Fees\FeesConfig
     */
    private $feesConfig;

    public function __construct(
        FeesConfig $feesConfig
    ) {

        $this->feesConfig = $feesConfig;
    }
    public function getFee(Operation $operation)
    {
        $fee = $this->feesConfig->fetch(
            $operation->getType(),
            $operation->getUserType()
        );
        if ($fee) {
            $totalFee = bcmul($operation->getSum(), $fee['percent'], 2);
            if ($operation->getType() === 'cash_in'
                && $this->feeIsBiggerThanMax($totalFee, $fee['max'])
            ) {
                $totalFee = $fee['max'];
            }

            if ($operation->getType() === 'cash_out'
                && $this->feeIsLowerThanMin($totalFee, $fee['min'])
            ) {
                $totalFee = $fee['min'];
            }

            return $totalFee;
        }
        //TODO return false
        return 0.00;
    }

    /**
     * @param string $fee
     * @param string $max
     *
     * @return bool
     */
    protected function feeIsBiggerThanMax($fee, $max)
    {
        return (bccomp($fee, $max) === 1) ? true : false;
    }

    /**
     * @param string $fee
     * @param string $min
     *
     * @return bool
     */
    protected function feeIsLowerThanMin($fee, $min)
    {
        return (bccomp($fee, $min) === -1) ? true : false;
    }
}
