<?php
namespace ExampleBundleTest\Service;

use ExampleBundle\Service\Math;
use ExampleBundleTest\AbstractTest;

class MathTest extends AbstractTest
{
    /**
     * @dataProvider converterProvider
     *
     * @param $expected
     * @param $input
     */
    public function testConverter($expected, $input)
    {
        $this->assertEquals(
            $expected,
            Math::convertToOutput($input)
        );
    }

    /**
     * @return array
     */
    public function converterProvider()
    {
        return [
            ['5.00', '4.991'],
            ['4.99', '4.990'],
        ];
    }
}
