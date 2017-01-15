<?php
namespace ExampleBundle\Service;

class OperationBuilder
{
    /**
     * @param $line
     *
     * @return Operation|null
     */
    public function fromString($line)
    {
        $items = explode(',', $line);
        if (count($items) >= 6) {
            return new Operation($items);
        }

        return null;
    }
}
