<?php
namespace ExampleBundle\Service\Fees;

use ExampleBundle\Service\MathInterface;
use ExampleBundle\Service\Operation;

abstract class AbstractFee implements MathInterface
{

    /**
     * @param Operation $operation
     * @param FeesConfig $feesConfig
     *
     * @return string
     */
    abstract public function calculateFee(Operation $operation, FeesConfig $feesConfig);

    /**
     * @param Operation $operation
     * @param FeesConfig $feesConfig
     *
     * @return array
     */
    public function fetchFee(Operation $operation, FeesConfig $feesConfig)
    {
        return $feesConfig->fetch(
            $operation->getType(),
            $operation->getUserType()
        );
    }
}
