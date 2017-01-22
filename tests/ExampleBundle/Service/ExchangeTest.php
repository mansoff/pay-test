<?php
namespace ExampleBundleTest\Service;

use ExampleBundle\Service\Exchange;

class ExchangeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Exchange
     */
    protected $testObj;

    public function setUp()
    {
        $this->testObj = new Exchange([
            'USD' => '1.1497',
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
            ['1.1497', 'USD', 'EUR', '1.00'],
            ['4.9899999999', 'USD', 'EUR', '4.3402626772'],
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
