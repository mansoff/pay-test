<?php
namespace ExampleBundle\Service\Fees;

use ExampleBundle\Service\Operation;

class LegalOutFee extends AbstractFee
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

        // if $fee['min'] > $totalFee
        if (bccomp($fee['min'], $totalFee, self::BC_SCALE) === 1) {
            $totalFee = $fee['min'];
        }

        return $totalFee;
    }
}
