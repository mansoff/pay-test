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
            return bcmul($sum, $this->rates[$to], self::BC_SCALE_EXCHANGE);
        }

        if ($to === 'EUR') {
            return bcmul($sum, $this->rates[$from], self::BC_SCALE_EXCHANGE);
        }

        throw new \Exception('From or to currency must be EUR');
    }
}
