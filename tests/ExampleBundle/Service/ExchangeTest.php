<?php
namespace ExampleBundleTest\Service;

use ExampleBundle\Service\Exchange;
use ExampleBundleTest\AbstractTest;

class ExchangeTest extends AbstractTest
{
    /**
     * @var Exchange
     */
    protected $testObj;

    public function setUp()
    {
        $this->testObj = new Exchange([
            'USD' => '1.1497',
            'JPY' => '129.53',
        ]);
    }

    /**
     * @dataProvider dataProviderConvert
     *
     * @param string $expected
     * @param string $from
     * @param string $to
     * @param string $sum
     */
    public function testConvert($expected, $from, $to, $sum)
    {
        $this->assertEquals(
            $expected,
            $this->testObj->convert($from, $to, $sum)
        );
    }

    /**
     * @return array
     */
    public function dataProviderConvert()
    {
        return [
            ['1.1497', 'EUR', 'USD', '1.00'],
            ['0.8697921196', 'USD', 'EUR', '1'],
            ['4.9899999999', 'EUR', 'USD', '4.3402626772'],
            ['1', 'JPY', 'EUR', '129.53'],
            ['129.53', 'EUR', 'JPY', '1'],
        ];
    }

    public function testNonExistCurrency()
    {
        $this->expectException(\Exception::class);

        $this->testObj->convert('EUR', 'YYY', '1.123');
    }


    public function testConvertWithoutEur()
    {
        $this->expectException(\Exception::class);

        $this->testObj->convert('XXX', 'YYY', '1.123');
    }
}
