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
        $minFee = $fee['min'];
        $totalFee = bcmul($operation->getSum(), $fee['percent'], self::BC_SCALE);

        if ($fee['currency'] !== $operation->getCurrency()) {
            $minFee = $this->exchange->convert(
                $fee['currency'],
                $operation->getCurrency(),
                $minFee
            );
        }

        // if $minFee < $totalFee
        if (bccomp($minFee, $totalFee, self::BC_SCALE) === 1) {
            $totalFee = $minFee;
        }

        return $totalFee;
    }
}
