<?php
namespace ExampleBundleTest\Service;

use ExampleBundle\Service\Operation;
use ExampleBundle\Service\OperationBuilder;
use ExampleBundleTest\AbstractTest;

class OperationBuilderTest extends AbstractTest
{
    /**
     * @var OperationBuilder
     */
    protected $testObj;

    public function setUp()
    {
        $this->testObj = new OperationBuilder();
    }

    /**
     * @dataProvider builderProvider
     *
     * @param $result
     * @param $input
     */
    public function testBuilder($result, $input)
    {
        $this->assertEquals(
            $result,
            $this->testObj->fromString($input)
        );
    }

    public function builderProvider()
    {
        return [
            [
                new Operation(['2016-01-09','1','legal','cash_in','200.01','EUR']),
                '2016-01-09,1,legal,cash_in,200.01,EUR',
            ],
            [
                null,
                '2016-01-09,1,legal,cash_in,200.01EUR',
            ]
        ];
    }
}
