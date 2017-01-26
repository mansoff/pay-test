<?php
namespace ExampleBundleTest\Service\Fee;

use ExampleBundle\Service\Exchange;
use ExampleBundle\Service\Fees\FeesConfig;
use ExampleBundle\Service\Fees\LegalInFee;
use ExampleBundle\Service\Fees\LegalOutFee;
use ExampleBundle\Service\Operation;
use ExampleBundleTest\AbstractTest;

class LegalOutFeeTest extends AbstractTest
{
    /**
     * @var LegalInFee
     */
    protected $testObj;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $exchange;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $feesConfig;

    public function setUp()
    {
        $this->exchange = $this->mockByClass(Exchange::class);
        $this->feesConfig = $this->mockByClass(FeesConfig::class);
        $this->testObj = new LegalOutFee(
            $this->exchange,
            $this->feesConfig
        );
    }

    /**
     * @dataProvider  maxFeeProvider
     *
     * @param $expected
     * @param $operation
     * @param $feeOption
     */
    public function testMinFee($expected, $operation, $feeOption)
    {
        $this->feesConfig->expects($this->once())
            ->method('fetch')
            ->willReturn($feeOption);

        $this->assertEquals(
            $expected,
            $this->testObj->calculateFee($operation)
        );
    }

    /**
     * @return array
     */
    public function maxFeeProvider()
    {
        return [
            [
                '0.55',
                (new Operation([
                    '2016-01-09','1','legal','cash_in','1.00','EUR'
                ])),
                [
                    'min' => '0.55',
                    'percent' => '0.001',
                    'currency' => 'EUR',
                ]
            ],
            [
                '12.345',
                (new Operation([
                    '2016-01-09','1','legal','cash_in','123.456','EUR'
                ])),
                [
                    'min' => '0.55',
                    'percent' => '0.1',
                    'currency' => 'EUR',
                ]
            ],
        ];
    }

    /**
     * @dataProvider  anotherCurrencyProvider
     *
     */
    public function testAnotherCurrency(
        $expected,
        $operation,
        $feeOption,
        $conversion
    ) {
        $this->feesConfig->expects($this->once())
            ->method('fetch')
            ->willReturn($feeOption);
        $this->exchange->expects($this->once())
            ->method('convert')
            ->willReturn($conversion);

        $this->assertEquals(
            $expected,
            $this->testObj->calculateFee($operation)
        );
    }

    public function anotherCurrencyProvider()
    {
        return [
            [
                '0.55',
                (new Operation([
                    '2016-01-09','1','legal','cash_in','1.00','LTL'
                ])),
                [
                    'min' => '0.01',
                    'percent' => '0.1',
                    'currency' => 'EUR',
                ],
                '0.55'
            ],[
                '12.345',
                (new Operation([
                    '2016-01-09','1','legal','cash_in','123.456','LTL'
                ])),
                [
                    'min' => '0.01',
                    'percent' => '0.1',
                    'currency' => 'EUR',
                ],
                '0.55'
            ],
        ];
    }
}
