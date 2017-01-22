<?php
namespace ExampleBundle\Service\Fees;

class FeesConfig
{
    //@todo move to DI
    protected $map = [
        'cash_in' => [
            'legal' => [
                'percent' => '0.0003',
                'max' => '5.00',
                'currency' => 'EUR',
            ],
            'natural' => [
                'percent' => '0.0003',
                'max' => '5.00',
                'currency' => 'EUR',
            ],
        ],
        'cash_out' => [
            'legal' => [
                'percent' => '0.003',
                'min' => '0.50',
                'currency' => 'EUR',
            ],
            'natural' => [
                'percent' => '0.003',
                'weekSum' => '1000.00',
                'currency' => 'EUR',
                'freeOperations' => '3',
            ],
        ],
    ];

    /**
     * @param string $operationType
     * @param string $userType
     *
     * @return array
     */
    public function fetch($operationType, $userType)
    {
        if (isset($this->map[$operationType])
            && isset($this->map[$operationType][$userType])
        ) {
            return $this->map[$operationType][$userType];
        }

        return [];
    }
}
