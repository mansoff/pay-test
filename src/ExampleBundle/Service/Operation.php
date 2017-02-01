<?php
namespace ExampleBundle\Service;

class Operation
{
    /**
     * @var string Y-m-d
     */
    private $date;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $userType;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $sum;

    /**
     * @var string
     */
    private $currency;

    /**
     * Operation constructor.
     * @param $input
     */
    public function __construct($input)
    {
        $this->date = $input[0];
        $this->id = $input[1];
        $this->userType = $input[2];
        $this->type = $input[3];
        $this->sum = $input[4];
        $this->currency = $input[5];
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * UserType + OperationType
     *
     * @return string
     */
    public function getFullType()
    {
        return $this->getUserType() . '_' . $this->getType();
    }
}
