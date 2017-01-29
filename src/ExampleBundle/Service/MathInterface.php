<?php
namespace ExampleBundle\Service;

interface MathInterface
{
    const BC_ZERO = '0.000';
    const BC_ZERO_OUTPUT = '0.00';

    const BC_SCALE = 3;
    const BC_SCALE_OUTPUT = 2;
    const BC_SCALE_EXCHANGE = 10;

    const BC_MAX_NEGATIVE = '-0.001';
    const BC_MIN_POSITIVE = '0.001';
}
