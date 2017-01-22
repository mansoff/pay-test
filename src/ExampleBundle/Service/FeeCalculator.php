<?php

namespace ExampleBundle\Service;

use ExampleBundle\Service\Fees\AbstractFee;
use ExampleBundle\Service\Fees\FeesConfig;

class FeeCalculator
{
    /**
     * @var Fees\FeesConfig
     */
    private $feesConfig;

    /**
     * @var Exchange
     */
    private $exchange;

    /**
     * @var array
     */
    private $feeMap;

    /**
     * FeeCalculator constructor.
     *
     * @param FeesConfig $feesConfig
     * @param Exchange $exchange
     * @param array $feeObjectsMap
     */
    public function __construct(
        FeesConfig $feesConfig,
        Exchange $exchange,
        array $feeObjectsMap
    ) {
        $this->feesConfig = $feesConfig;
        $this->exchange = $exchange;
        $this->feeMap = $feeObjectsMap;
    }

    /**
     * @param Operation $operation
     *
     * @return string
     * @throws \Exception
     */
    public function getFee(Operation $operation)
    {
        if (empty($this->feeMap[$operation->getFullType()])) {
            throw new \Exception('Not defined operation type');
        }

        $feeRule = $this->feeMap[$operation->getFullType()];
        if (!$feeRule instanceof AbstractFee) {
            throw new \Exception('Not AbstractFee instance');
        }

        return $feeRule->calculateFee($operation);
    }
}
