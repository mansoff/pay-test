<?php
namespace ExampleBundle\Service;

class Math implements MathInterface
{
    /**
     * Converts 4.991 -> 5.00 ; 4.999 -> 5.00 & etc
     *
     *
     * 4.991 + 0.009 = 5.000 => 5.00
     * 4.990 + 0.009 = 4.999 => 4.99
     *
     * @param $sum
     * @return string
     */
    public function convertToOutput($sum)
    {
        // 0.09 for BC_SCALE == 2
        // 0.009 for BC_SCALE == 3
        $minimalNineNumber = bcdiv(
            '9',
            bcpow('10', self::BC_SCALE),
            self::BC_SCALE
        );

        return bcadd(
            $sum,
            $minimalNineNumber,
            self::BC_SCALE_OUTPUT
        );
    }
}
