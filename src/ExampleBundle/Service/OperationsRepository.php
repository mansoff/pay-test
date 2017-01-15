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

    /**
     * @param $fileName
     *
     * @return \Generator|Operation[]|null
     */
    public function getOperations($fileName)
    {
        $lines = $this->gateway->fetchAll($fileName);
        foreach ($lines as $line) {
            yield $this->builder->fromString($line);
        }
    }
}
