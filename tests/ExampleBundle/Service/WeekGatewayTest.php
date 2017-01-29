<?php
namespace ExampleBundleTest\Service;

use ExampleBundle\Service\WeekGateway;
use ExampleBundleTest\AbstractTest;

class WeekGatewayTest extends AbstractTest
{
    /**
     * @var WeekGateway
     */
    protected $testObj;

    /**
     * setUp
     */
    public function setUp()
    {
        $this->testObj = new WeekGateway();
    }

    public function testCounter()
    {
        $user = 'user';
        $date = '2017-01-02';
        $this->assertEquals(
            [
                'sum' => '1.00',
                'count' => 0,
            ],
            $this->testObj->getUserWeekData(
                $user,
                $date,
                '1.00'
            )
        );
        $this->assertEquals(
            1,
            $this->testObj->incCounter($user, $date)
        );
        $this->assertEquals(
            2,
            $this->testObj->incCounter($user, $date)
        );
        $this->assertEquals(
            3,
            $this->testObj->incCounter($user, $date)
        );
        $this->assertEquals(
            [
                'sum' => '1.00',
                'count' => 3,
            ],
            $this->testObj->getUserWeekData(
                $user,
                $date,
                '1.00'
            )
        );
    }

    public function testUpdateSumAndCounter()
    {
        $user = 'user';
        $date = '2017-01-02';
        $this->assertEquals(
            [
                'sum' => '1.00',
                'count' => 0,
            ],
            $this->testObj->getUserWeekData(
                $user,
                $date,
                '1.00'
            )
        );
        $this->assertEquals(
            1,
            $this->testObj->incCounter($user, $date)
        );
        $this->assertEquals(
            true,
            $this->testObj->updateUserWeekSum(
                $user,
                $date,
                '1.23'
            )
        );
        $this->assertEquals(
            [
                'sum' => '1.23',
                'count' => 1,
            ],
            $this->testObj->getUserWeekData(
                $user,
                $date,
                '1.00'
            )
        );
    }

    public function testTwoWeeks()
    {
        $user = 'foo.user';
        $this->testObj->updateUserWeekSum($user, '2017-02-08', '1.23');

        $this->assertEquals(
            [
                'sum' => '1.23',
                'count' => 0,
            ],
            $this->testObj->getUserWeekData($user, '2017-02-08', '0.00')
        );

        $this->testObj->updateUserWeekSum($user, '2017-02-09', '4.56');

        $this->assertEquals(
            [
                'sum' => '4.56',
                'count' => 0,
            ],
            $this->testObj->getUserWeekData($user, '2017-02-08', '0.00')
        );
    }

    /**
     * @dataProvider weeksProvider
     */
    public function testGetWeekByDate()
    {
        $this->testObj;
        $method = $this->getInvisibleMethod(
            WeekGateway::class,
            'getWeekByDate'
        );
        $this->assertEquals(
            '1',
            $method->invoke($this->testObj, '2017-01-02')
        );
    }

    public function weeksProvider()
    {
        return [
            ['52', '2017-01-01'],
            ['1', '2017-01-02'],
            ['1', '2017-01-03'],
            ['1', '2017-01-04'],
            ['1', '2017-01-05'],
            ['1', '2017-01-06'],
            ['1', '2017-01-07'],
            ['1', '2017-01-08'],
            ['2', '2017-01-09'],
        ];
    }
}
