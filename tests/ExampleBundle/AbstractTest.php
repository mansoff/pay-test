<?php
namespace ExampleBundleTest;

abstract class AbstractTest extends \PHPUnit_Framework_TestCase
{
    public function mockBy($className)
    {
        $this->getMockBuilder();
    }
}
