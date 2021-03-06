<?php
namespace ExampleBundle\Service\Fees;

use ExampleBundle\Service\Operation;

class LegalInFee extends AbstractFee
{
    /**
     * @param Operation $operation
     *
     * @return string
     */
    public function calculateFee(Operation $operation)
    {
        $fee = $this->fetchFee($operation);
        $maxFee = $fee['max'];
        $totalFee = bcmul($operation->getSum(), $fee['percent'], self::BC_SCALE);

        if ($fee['currency'] !== $operation->getCurrency()) {
            $maxFee = $this->exchange->convert(
                $fee['currency'],
                $operation->getCurrency(),
                $maxFee
            );
        }

        //if $totalFee > $maxFee
        if (bccomp($totalFee, $maxFee, self::BC_SCALE) === 1) {
            $totalFee = $maxFee;
        }

        return $totalFee;
    }
}
