<?php
namespace ExampleBundleTest\Service;

use ExampleBundle\Service\CsvFormatter;
use ExampleBundle\Service\OperationsGateway;
use ExampleBundleTest\AbstractTest;

class OperationsGatewayTest extends AbstractTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $csvFormatter;

    /**
     * @var OperationsGateway
     */
    protected $testObj;

    /**
     * setUp
     */
    public function setUp()
    {
        $this->csvFormatter = $this->mockByClass(CsvFormatter::class);
        $this->testObj = new OperationsGateway(
            $this->csvFormatter
        );
    }

    public function testFetch()
    {
        $this->csvFormatter
            ->expects($this->once())
            ->method('formatContent')
            ->willReturn('foo.bar');

        $this->assertEquals(
            'foo.bar',
            $this->testObj->fetchAll(__DIR__.'/test.csv')
        );
    }
}
