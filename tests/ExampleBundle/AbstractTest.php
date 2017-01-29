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

    /**
     * @param $className
     * @param $methodName
     *
     * @return \ReflectionMethod
     */
    public function getInvisibleMethod($className, $methodName)
    {
        $class = new \ReflectionClass($className);
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }
}
