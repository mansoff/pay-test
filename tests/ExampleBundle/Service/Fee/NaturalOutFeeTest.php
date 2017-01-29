<?php

namespace ExampleBundleTest\Service\Fee;

use ExampleBundle\Service\Exchange;
use ExampleBundle\Service\Fees\FeesConfig;
use ExampleBundle\Service\Fees\NaturalOutFee;
use ExampleBundle\Service\MathInterface;
use ExampleBundle\Service\Operation;
use ExampleBundle\Service\WeekGateway;
use ExampleBundleTest\AbstractTest;

class NaturalOutFeeTest extends AbstractTest implements MathInterface
{
    /**
     * @var NaturalOutFee
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

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $weekGateway;

    /**
     * setUp
     */
    public function setUp()
    {
        $this->exchange = $this->mockByClass(Exchange::class);
        $this->feesConfig = $this->mockByClass(FeesConfig::class);
        $this->weekGateway = $this->mockByClass(WeekGateway::class);
        $this->testObj = new NaturalOutFee(
            $this->exchange,
            $this->feesConfig,
            $this->weekGateway
        );
    }

    /**
     * @dataProvider greaterZeroProvider
     *
     * @param bool $expected
     * @param string $sum
     */
    public function testGreaterZero($expected, $sum)
    {
        $method = $this->getInvisibleMethod(
            NaturalOutFee::class,
            'isGreaterThanZero'
        );
        $this->assertEquals(
            $expected,
            $method->invoke($this->testObj, $sum)
        );
    }

    /**
     * @return array
     */
    public function greaterZeroProvider()
    {
        return [
            [false, self::BC_ZERO],
            [false, self::BC_MAX_NEGATIVE],
            [true, self::BC_MIN_POSITIVE],
        ];
    }

    /**
     * @dataProvider sumGreaterThanFreeOperationProvider
     *
     * @param $expected
     * @param $sum
     * @param $maxFreeOperation
     */
    public function testSumGreaterThanFreeOperation(
        $expected,
        $sum,
        $maxFreeOperation
    ) {
        $method = $this->getInvisibleMethod(
            NaturalOutFee::class,
            'getSumGreaterThanFreeOperation'
        );
        $this->assertEquals(
            $expected,
            $method->invoke(
                $this->testObj,
                $sum,
                ['sum' => $maxFreeOperation]
            )
        );
    }

    /**
     * @return array
     */
    public function sumGreaterThanFreeOperationProvider()
    {
        return [
            // result = x - y
            ['-0.001', '0.000', '0.001'],
            ['0.000', '0.001', '0.001'],
            ['0.001', '0.001', '0.000'],
        ];
    }

    /**
     * @dataProvider freeOperationsProvider
     *
     * @param $expected
     * @param $freeOperations
     * @param $counter
     */
    public function testFreeOperationsCounter(
        $expected,
        $freeOperations,
        $counter
    ) {
        $method = $this->getInvisibleMethod(
            NaturalOutFee::class,
            'isFreeOperationAvailable'
        );
        $this->assertEquals(
            $expected,
            $method->invoke(
                $this->testObj,
                ['count' => $counter],
                $freeOperations
            )
        );
    }

    /**
     * @return array
     */
    public function freeOperationsProvider()
    {
        return [
            [true, 1, 1],
            [false, 1, 2],
            [false, 0, 1],
        ];
    }

    /**
     * @dataProvider freeSumProvider
     *
     * @param $expected
     * @param $sum
     */
    public function testFreeSumAvailable(
        $expected,
        $sum
    ) {
        $method = $this->getInvisibleMethod(
            NaturalOutFee::class,
            'isFreeSumAvailable'
        );
        $this->assertEquals(
            $expected,
            $method->invoke(
                $this->testObj,
                ['sum' => $sum]
            )
        );
    }

    /**
     * @return array
     */
    public function freeSumProvider()
    {
        return [
            [true, self::BC_MIN_POSITIVE],
            [false, self::BC_ZERO],
            [false, self::BC_MAX_NEGATIVE],
        ];
    }

    /**
     * @dataProvider calculateFeeProvider
     *
     * @param $expected
     * @param Operation $operation
     * @param array $fee
     * @param array $weekData
     */
    public function testCalculateFee(
        $expected,
        Operation $operation,
        array $fee,
        array $weekData
    ) {
        $this->feesConfig->expects($this->once())
            ->method('fetch')
            ->willReturn($fee);

        $this->weekGateway->expects($this->once())
            ->method('getUserWeekData')
            ->willReturn($weekData);

        $this->weekGateway->expects($this->once())
            ->method('incCounter')
            ->willReturn(++$weekData['count']);

        $this->assertEquals(
            $expected,
            $this->testObj->calculateFee($operation)
        );
    }

    /**
     * @return array
     */
    public function calculateFeeProvider()
    {
        return [
            [
                'expected'=> '3.000',
                new Operation(['2016-01-09','1','natural', 'cash_out','1000.00','EUR']),
                'fee' => [
                    'percent' => '0.003',
                    'week_sum' => '0.00',
                    'currency' => 'EUR',
                    'free_operations' => '3',
                ],
                'weekData' => [
                    'sum' => '0.00',
                    'count' => 0,
                ],
            ],
            [
                'expected'=> '2.700',
                new Operation(['2016-01-09','1','natural', 'cash_out','1000.00','EUR']),
                'fee' => [
                    'percent' => '0.003',
                    'week_sum' => '100.00',
                    'currency' => 'EUR',
                    'free_operations' => '3',
                ],
                'weekData' => [
                    'sum' => '100.00',
                    'count' => 0,
                ],
            ],
            [
                'expected'=> '3.000',
                new Operation(['2016-01-09','1','natural', 'cash_out','1000.00','EUR']),
                'fee' => [
                    'percent' => '0.003',
                    'week_sum' => '100.00',
                    'currency' => 'EUR',
                    'free_operations' => '3',
                ],
                'weekData' => [
                    'sum' => '100.00',
                    'count' => 3,
                ],
            ],
        ];
    }

    /**
     * @dataProvider calculateFeeProviderCurrency
     *
     * @param $expected
     * @param Operation $operation
     * @param array $fee
     * @param string $convertedSum
     * @param array $weekData
     */
    public function testCalculateAnotherCurrencyFee(
        $expected,
        Operation $operation,
        array $fee,
        $convertedSum,
        array $weekData
    ) {
        $this->feesConfig->expects($this->once())
            ->method('fetch')
            ->willReturn($fee);

        $this->exchange->expects($this->once())
            ->method('convert')
            ->willReturn($convertedSum);

        $this->weekGateway->expects($this->once())
            ->method('getUserWeekData')
            ->willReturn($weekData);

        $this->weekGateway->expects($this->once())
            ->method('incCounter')
            ->willReturn(++$weekData['count']);

        $this->assertEquals(
            $expected,
            $this->testObj->calculateFee($operation)
        );
    }

    /**
     * @return array
     */
    public function calculateFeeProviderCurrency()
    {
        return [
            [
                'expected'=> '3.000',
                new Operation(['2016-01-09','1','natural', 'cash_out','1234.00','FOO']),
                'fee' => [
                    'percent' => '0.003',
                    'week_sum' => '0.00',
                    'currency' => 'EUR',
                    'free_operations' => '3',
                ],
                'convertedSum' => '1000.00',
                'weekData' => [
                    'sum' => '0.00',
                    'count' => 0,
                ],
            ],
            [
                'expected'=> '2.700',
                new Operation(['2016-01-09','1','natural', 'cash_out','5678,00','FOO']),
                'fee' => [
                    'percent' => '0.003',
                    'week_sum' => '100.00',
                    'currency' => 'EUR',
                    'free_operations' => '3',
                ],
                'convertedSum' => '1000.00',
                'weekData' => [
                    'sum' => '100.00',
                    'count' => 0,
                ],
            ],
            [
                'expected'=> '3.000',
                new Operation(['2016-01-09','1','natural', 'cash_out','666.99','FOO']),
                'fee' => [
                    'percent' => '0.003',
                    'week_sum' => '100.00',
                    'currency' => 'EUR',
                    'free_operations' => '3',
                ],
                'convertedSum' => '1000.00',
                'weekData' => [
                    'sum' => '100.00',
                    'count' => 3,
                ],
            ],
        ];
    }
}
