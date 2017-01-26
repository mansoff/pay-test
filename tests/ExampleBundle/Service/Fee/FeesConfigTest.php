<?php

namespace ExampleBundleTest\Service\Fee;

use ExampleBundle\Service\Fees\FeesConfig;
use ExampleBundleTest\AbstractTest;

class FeesConfigTest extends AbstractTest
{
    /**
     * @dataProvider fetchProvider
     *
     * @param $expected
     * @param $map
     * @param $userType
     * @param $type
     */
    public function testFetch($expected, $map, $userType, $type)
    {
        $testObj = new FeesConfig($map);

        $this->assertEquals(
            $expected,
            $testObj->fetch($type, $userType)
        );
    }

    public function fetchProvider()
    {
        return [
            [
                [1, 2, 3],
                [
                    'foo' => [
                        'bar' => [1, 2, 3],
                    ],
                ],
                'bar',
                'foo'
            ],
            [
                [],
                [],
                'bar',
                'foo'
            ],
            [
                [],
                [],
                '',
                ''
            ],
        ];
    }
}
