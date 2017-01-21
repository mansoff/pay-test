<?php
namespace ExampleBundle\Service\Fees;

use ExampleBundle\Service\Operation;

class LegalInFee extends AbstractFee
{
    /**
     * @param Operation $operation
     * @param FeesConfig $feesConfig
     *
     * @return string
     */
    public function calculateFee(Operation $operation, FeesConfig $feesConfig)
    {
        $fee = $this->fetchFee($operation, $feesConfig);
        $totalFee = bcmul($operation->getSum(), $fee['percent'], 2);

        //if $totalFee > $fee['max']
        if (bccomp($totalFee, $fee['max'], self::BC_SCALE) === 1) {
            $totalFee = $fee['max'];
        }

        return $totalFee;
    }
}
