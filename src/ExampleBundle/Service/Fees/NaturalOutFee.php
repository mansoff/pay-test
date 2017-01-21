<?php
namespace ExampleBundle\Service\Fees;

use ExampleBundle\Service\Operation;

class NaturalOutFee extends AbstractFee
{
    public function calculateFee(Operation $operation, FeesConfig $feesConfig)
    {
        return '1.00';
    }
}