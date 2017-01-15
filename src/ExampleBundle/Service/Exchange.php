<?php
namespace ExampleBundle\Service;

class Exchange
{
    /**
     * @var array
     */
    protected $rates = [
        'USD' => '1.1497',
        'JPY' => '129.53',
    ];

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
            return bcmul($sum, $this->rates[$to], 2);
        }

        if ($to === 'EUR') {
            return bcmul($sum, $this->rates[$from], 2);
        }

        throw new \Exception('From or to currency must be EUR');
    }
}
