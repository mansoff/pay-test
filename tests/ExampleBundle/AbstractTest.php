<?php
namespace ExampleBundleTest;

abstract class AbstractTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $className
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function mockByClass($className)
    {
        return $this->getMockBuilder($className)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
