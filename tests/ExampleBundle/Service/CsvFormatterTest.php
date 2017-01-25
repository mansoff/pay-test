<?php
namespace ExampleBundleTest\Service;

use ExampleBundle\Service\CsvFormatter;
use ExampleBundleTest\AbstractTest;

class CsvFormatterTest extends AbstractTest
{
    /**
     * @var CsvFormatter
     */
    protected $testObj;

    /**
     * setUp
     */
    public function setUp()
    {
        $this->testObj = new CsvFormatter();
    }

    /**
     * @dataProvider formatterDataProvider
     *
     * @param $result
     * @param $input
     */
    public function testFormatter($result, $input)
    {
        $this->assertEquals(
            $result,
            $this->testObj->formatContent($input)
        );
    }

    /**
     * @return array
     */
    public function formatterDataProvider()
    {
        return [
            [
                [1,2,3,4],
                "1\r2\r3\r4",
            ],
            [
                [1,2,3,4],
                "1\n2\n3\n4",
            ],
            [
                [1,2,3,4],
                "1\r\n2\r\n3\r\n4",
            ],
            [
                [1,2,3,4],
                "1\n\r2\n\r3\n\r4",
            ],
        ];
    }
}
