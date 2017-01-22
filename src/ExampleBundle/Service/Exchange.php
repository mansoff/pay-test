<?php
namespace ExampleBundle\Service;

class Exchange implements MathInterface
{
    /**
     * @var array
     */
    protected $rates = [];

    /**
     * Exchange constructor.
     * @param array $rates
     */
    public function __construct(array $rates)
    {
        $this->rates = $rates;
    }

    /**
     * @param string $from
     * @param string $to
     * @param string $sum
     *
     * @return string
     * @throws \Exception
     */
    public function convert($from, $to, $sum)
    {
        if ($from === 'EUR') {
            $rateIndex = $to;
        } elseif ($to === 'EUR') {
            $rateIndex = $from;
        } else {
            throw new \Exception('From or to currency must be EUR');
        }

        if (!isset($this->rates[$rateIndex])) {
            throw new \Exception(
                sprintf(
                    'We don\'t have rates from EUR to ',
                    $rateIndex
                )
            );
        }

        return bcmul($sum, $this->rates[$rateIndex], self::BC_SCALE_EXCHANGE);
    }
}
