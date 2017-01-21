<?php
namespace ExampleBundle\Service\Fees;

use ExampleBundle\Service\Exchange;
use ExampleBundle\Service\Operation;

class NaturalInFee extends AbstractFee
{
    protected $exchange;

    public function __construct(
        Exchange $exchange
    ) {
        $this->exchange = $exchange;
    }

    /**
     * @param Operation $operation
     * @param FeesConfig $feesConfig
     *
     * @return string
     */
    public function calculateFee(Operation $operation, FeesConfig $feesConfig)
    {
        $fee = $this->fetchFee($operation, $feesConfig);
        $maxFee = $fee['max'];
        $totalFee = bcmul($operation->getSum(), $fee['percent'], self::BC_SCALE);

        if ($fee['currency'] !== $operation->getCurrency()) {
            $maxFee = $this->exchange->convert(
                $fee['currency'],
                $operation->getCurrency(),
                $maxFee
            );
        }

        //if $totalFee > $fee['max']
        if (bccomp($totalFee, $maxFee, self::BC_SCALE) === 1) {
            $totalFee = $maxFee;
        }

        return $totalFee;
    }
}
