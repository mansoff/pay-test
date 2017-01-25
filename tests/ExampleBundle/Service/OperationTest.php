<?php

namespace ExampleBundleTest\Service;

use ExampleBundle\Service\Operation;
use ExampleBundleTest\AbstractTest;

class OperationTest extends AbstractTest
{
    /**
     * @dataProvider operationProvider
     *
     * @param $result
     * @param $input
     */
    public function testOperation($result, $input)
    {
        $testObj = new Operation($input);
        $this->assertEquals($result['date'], $testObj->getDate());
        $this->assertEquals($result['user_id'], $testObj->getId());
        $this->assertEquals($result['user_type'], $testObj->getUserType());
        $this->assertEquals($result['type'], $testObj->getType());
        $this->assertEquals($result['sum'], $testObj->getSum());
        $this->assertEquals($result['currency'], $testObj->getCurrency());
        $this->assertEquals(
            $result['user_type'].'_'.$result['type'],
            $testObj->getFullType()
        );
    }

    public function operationProvider()
    {
        return [
            [
                [
                    'date' => '2016-01-09',
                    'user_id' => '1',
                    'user_type' => 'legal',
                    'type' => 'cash_in',
                    'sum' => '200.01',
                    'currency' => 'EUR',
                ],
                ['2016-01-09','1','legal','cash_in','200.01','EUR'],
            ]
        ];
    }
}
