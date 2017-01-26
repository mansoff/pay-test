<?php
namespace ExampleBundleTest\Service;

use ExampleBundle\Service\OperationBuilder;
use ExampleBundle\Service\OperationsGateway;
use ExampleBundle\Service\OperationsRepository;
use ExampleBundleTest\AbstractTest;

class OperationsRepositoryTest extends AbstractTest
{
    /**
     * @var OperationsRepository
     */
    protected $testObj;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $operationsGateway;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $operationBuilder;

    /**
     * setUp
     */
    public function setUp()
    {
        $this->operationsGateway = $this->mockByClass(OperationsGateway::class);
        $this->operationBuilder = $this->mockByClass(OperationBuilder::class);

        $this->testObj = new OperationsRepository(
            $this->operationsGateway,
            $this->operationBuilder
        );
    }

    public function testGetter()
    {
        $fileName = 'fileName.foo.bar';
        $this->operationsGateway->expects($this->once())
            ->method('fetchAll')
            ->willReturn(['foo', 'bar']);
        $this->operationBuilder->expects($this->exactly(2))
            ->method('fromString');

        foreach ($this->testObj->getOperations($fileName) as $value) {
            unset($value);
        }
    }
}
