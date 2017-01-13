<?php
namespace ExampleBundle\Service;

class OperationsRepository
{
    /**
     * @var OperationsGateway
     */
    private $gateway;

    /**
     * @var OperationBuilder
     */
    private $builder;

    /**
     * OperationsRepository constructor.
     * @param OperationsGateway $gateway
     * @param OperationBuilder $builder
     */
    public function __construct(
        OperationsGateway $gateway,
        OperationBuilder $builder
    ) {

        $this->gateway = $gateway;
        $this->builder = $builder;
    }

    public function getOperations($fileName)
    {
        $result = [];
        $lines = $this->gateway->fetchAll($fileName);
        foreach ($lines as $line) {
            if ($operation = $this->builder->fromString($line)) {
                $result[] = $operation;
            }
        }

        return $result;
    }
}
