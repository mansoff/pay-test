<?php
namespace ExampleBundle\Service;

class OperationBuilder
{
    public function fromString($line)
    {
        $items = explode(',', $line);
        if (count($items) >= 6) {
            return new Operation($items);
        }

        return null;
    }
}
