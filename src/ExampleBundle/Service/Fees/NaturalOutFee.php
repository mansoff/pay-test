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
                $operation->getCurrency(),
                $fee['currency'],
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
        $this->weekGateway->incCounter($user, $date);

        if (!$this->isFreeOperationAvailable($weekData, $freeOperations)) {
            return $sum;
        }

        if (!$this->isFreeSumAvailable($weekData)) {
            return $sum;
        }

        //sumGreater = sum - weekFreeSum
        $sumGreaterThanFreeOperation = $this->getSumGreaterThanFreeOperation($sum, $weekData);

        if ($this->isGreaterThanZero($sumGreaterThanFreeOperation)) {
            $this->weekGateway
                ->updateUserWeekSum($user, $date, self::BC_ZERO);

            return $sumGreaterThanFreeOperation;
        }

        //$freeReminder = -1 * (sumLowerThanWeekFreeSum - weekFreeSum)
        $freeReminder = bcmul(
            $sumGreaterThanFreeOperation,
            '-1',
            self::BC_SCALE
        );
            ;
        $this->weekGateway
            ->updateUserWeekSum($user, $date, $freeReminder);

        return self::BC_ZERO;
    }

    /**
     * @param $weekData
     *
     * @return bool
     */
    protected function isFreeSumAvailable($weekData)
    {
        if (bccomp($weekData['sum'], self::BC_ZERO, self::BC_SCALE) == 0) {
            return false;
        }
        return true;
    }

    /**
     * @param array $weekData
     * @param int $freeOperations
     *
     * @return bool
     */
    protected function isFreeOperationAvailable(array $weekData, $freeOperations)
    {
        if ($weekData['count'] > $freeOperations) {
            return false;
        }

        return true;
    }

    /**
     * @param string $input
     * @param array $weekData
     *
     * @return string $input - $weekFreeSum
     */
    protected function getSumGreaterThanFreeOperation(
        $input,
        array $weekData
    ) {
        return bcsub(
            $input,
            $weekData['sum'],
            self::BC_SCALE
        );
    }

    /**
     * @param string $sum
     *
     * @return bool
     */
    protected function isGreaterThanZero($sum)
    {
        if (bccomp($sum, self::BC_ZERO, self::BC_SCALE) == 1) {
            return true;
        }

        return false;
    }
}
