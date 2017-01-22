<?php
namespace ExampleBundle\Service\Fees;

use ExampleBundle\Service\Operation;

class NaturalOutFee extends AbstractFee
{
    public function calculateFee(Operation $operation)
    {
//        'percent' => '0.003',
//        'weekSum' => '1000.00',
//        'currency' => 'EUR',
//        'freeOperations' => '3',

        $fee = $this->fetchFee($operation);
        $totalFee = bcmul($operation->getSum(), $fee['percent'], self::BC_SCALE);

//        if ($fee['currency'] !== $operation->getCurrency()) {
//            $totalFee = $this->exchange->convert(
//                $fee['currency'],
//                $operation->getCurrency(),
//                $totalFee
//            );
//        }

//        // if $minFee < $totalFee
//        if (bccomp($minFee, $totalFee, self::BC_SCALE) === 1) {
//            $totalFee = $minFee;
//        }

        return $totalFee;
    }
}
