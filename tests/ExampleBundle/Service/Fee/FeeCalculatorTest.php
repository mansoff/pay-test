<?php

namespace ExampleBundleTest\Service\Fee;

use ExampleBundle\Service\Exchange;
use ExampleBundle\Service\Fees\FeeCalculator;
use ExampleBundle\Service\Fees\FeesConfig;
use ExampleBundle\Service\Fees\NaturalInFee;
use ExampleBundle\Service\Operation;
use ExampleBundleTest\AbstractTest;

class FeeCalculatorTest extends AbstractTest
{

    /**
     * @var FeeCalculator
     */
    protected $testObj;
    protected $exchange;
    protected $feesConfig;
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $feeRule;

    /**
     * setUp
     */
    public function setUp()
    {
        $this->exchange = $this->mockByClass(Exchange::class);
        $this->feesConfig = $this->mockByClass(FeesConfig::class);
        $this->feeRule = $this->mockByClass(NaturalInFee::class);

        $map = ['natural_cash_in' => $this->feeRule];
        $this->testObj = new FeeCalculator(
            $this->feesConfig,
            $this->exchange,
            $map
        );
    }

    public function testFeeGetter()
    {
        $operation = new Operation(
            ['1', '2', 'natural', 'cash_in', '5', '6']
        );
        $this->feeRule->expects($this->once())
            ->method('calculateFee');
        $this->testObj->getFee($operation);
    }

    public function testNotDefinedOperation()
    {
        $operation = new Operation(
            ['1', '2', 'foo', 'bar', '5', '6']
        );
        $this->expectException(\Exception::class);
        $this->testObj->getFee($operation);
    }

    public function testBadMappings()
    {
        $operation = new Operation(
            ['1', '2', 'natural', 'cash_in', '5', '6']
        );
        $map = ['natural_cash_in' => 'how about string?'];
        $this->testObj = new FeeCalculator(
            $this->feesConfig,
            $this->exchange,
            $map
        );
        $this->expectException(\Exception::class);

        $this->testObj->getFee($operation);
    }
}
