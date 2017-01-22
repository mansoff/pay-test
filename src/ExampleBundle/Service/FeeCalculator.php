<?php

namespace ExampleBundle\Service;

use ExampleBundle\Service\Fees\FeesConfig;
use ExampleBundle\Service\Fees\LegalInFee;
use ExampleBundle\Service\Fees\LegalOutFee;
use ExampleBundle\Service\Fees\NaturalInFee;
use ExampleBundle\Service\Fees\NaturalOutFee;

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
     * FeeCalculator constructor.
     *
     * @param FeesConfig $feesConfig
     * @param Exchange $exchange
     */
    public function __construct(
        FeesConfig $feesConfig,
        Exchange $exchange
    ) {
        $this->feesConfig = $feesConfig;
        $this->exchange = $exchange;
    }

    /**
     * @param Operation $operation
     *
     * @return string
     * @throws \Exception
     */
    public function getFee(Operation $operation)
    {
        switch ($operation->getFullType()) {
            case 'natural_cash_in':
                return (new NaturalInFee($this->exchange))
                    ->calculateFee($operation, $this->feesConfig);
            case 'natural_cash_out':
                return (new NaturalOutFee($this->exchange))
                    ->calculateFee($operation, $this->feesConfig);
            case 'legal_cash_in':
                return (new LegalInFee($this->exchange))
                    ->calculateFee($operation, $this->feesConfig);
            case 'legal_cash_out':
                return (new LegalOutFee($this->exchange))
                    ->calculateFee($operation, $this->feesConfig);
            default:
                throw new \Exception('Not defined operation type');
        }
    }
}
