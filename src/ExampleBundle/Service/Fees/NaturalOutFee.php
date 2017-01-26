<?php
namespace ExampleBundle\Service\Fees;

use ExampleBundle\Service\Exchange;
use ExampleBundle\Service\Operation;
use ExampleBundle\Service\WeekGateway;

class NaturalOutFee extends AbstractFee
{
    /**
     * @var WeekGateway
     */
    private $weekGateway;

    /**
     * NaturalOutFee constructor.
     * @param Exchange $exchange
     * @param FeesConfig $feesConfig
     * @param WeekGateway $weekGateway
     */
    public function __construct(
        Exchange $exchange,
        FeesConfig $feesConfig,
        WeekGateway $weekGateway
    ) {
        parent::__construct($exchange, $feesConfig);
        $this->weekGateway = $weekGateway;
    }

    /**
     * @var array
     */
    protected $history = [];

    /**
     * @param Operation $operation
     *
     * @return string
     */
    public function calculateFee(Operation $operation)
    {
        $fee = $this->fetchFee($operation);

        $operationSum = $operation->getSum();
        if ($operation->getCurrency() != $fee['currency']) {
            $operationSum = $this->exchange->convert(
                $fee['currency'],
                $operation->getCurrency(),
                $operation->getSum()
            );
        }

        $weekSum = $this->getWeekTaxableSum(
            $fee['week_sum'],
            $fee['free_operations'],
            $operation->getId(),
            $operation->getDate(),
            $operationSum
        );

        $totalFee = bcmul(
            $weekSum,
            $fee['percent'],
            self::BC_SCALE
        );

        return $totalFee;
    }

    /**
     * @param string $freeFee
     * @param int $freeOperations
     * @param int $user
     * @param string $date
     * @param string $sum
     *
     * @return string
     */
    protected function getWeekTaxableSum(
        $freeFee,
        $freeOperations,
        $user,
        $date,
        $sum
    ) {
        $weekData = $this->weekGateway
            ->getUserWeekData($user, $date, $freeFee);

        if ($weekData['count'] > $freeOperations) {
            return $sum;
        }
        if (bccomp($weekData['sum'], '0.00') == 0) {
            return $sum;
        }

        $sum = bcsub(
            $sum,
            $weekData['sum'],
            self::BC_SCALE
        );
        if (bccomp($sum, '0.00') == -1) {
            $updatedSum = bcmul(
                $sum,
                '-1',
                self::BC_SCALE
            );
            $sum = '0.00';
        } else {
            $updatedSum = '0.00';
        }
        $this->weekGateway
            ->insertUserWeekSum($user, $date, $updatedSum);

        return $sum;
    }
}
