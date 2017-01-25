<?php
namespace ExampleBundle\Service\Fees;

class FeesConfig
{
    public function __construct(array $feesMap)
    {
        $this->map = $feesMap;
    }

    //@todo move to DI
    protected $map = [];

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
