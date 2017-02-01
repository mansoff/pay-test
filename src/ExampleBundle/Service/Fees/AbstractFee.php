<?php
namespace ExampleBundle\Service\Fees;

use ExampleBundle\Service\Exchange;
use ExampleBundle\Service\MathInterface;
use ExampleBundle\Service\Operation;

abstract class AbstractFee implements MathInterface
{
    /**
     * @var Exchange
     */
    protected $exchange;

    /**
     * @var FeesConfig
     */
    private $feesConfig;

    /**
     * NaturalInFee constructor.
     *
     * @param Exchange $exchange
     * @param FeesConfig $feesConfig
     */
    public function __construct(
        Exchange $exchange,
        FeesConfig $feesConfig
    ) {
        $this->exchange = $exchange;
        $this->feesConfig = $feesConfig;
    }

    /**
     * @param Operation $operation
     *
     * @return string
     */
    abstract public function calculateFee(Operation $operation);

    /**
     * @param Operation $operation
     *
     * @return array
     */
    public function fetchFee(Operation $operation)
    {
        return $this->feesConfig->fetch(
            $operation->getType(),
            $operation->getUserType()
        );
    }
}
