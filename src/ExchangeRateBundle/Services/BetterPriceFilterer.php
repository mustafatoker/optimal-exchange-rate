<?php

namespace ExchangeRateBundle\Services;

final class BetterPriceFilterer
{
    /**
     * @var array
     */
    private $currencyRates = [];

    /**
     * BetterPriceFilterer constructor.
     * @param array $currencyRates
     */
    public function __construct(array $currencyRates)
    {
        $this->currencyRates = $currencyRates;
    }

    /**
     * @return array
     */
    public function filter()
    {
        $filterParityRates = [];
        foreach ($this->currencyRates as $parity => $rates)
        {
            $parityRates = array_column($rates, 'amount');
            $arrayKeyHasMinAmount = current(array_keys($parityRates, min($parityRates)));

            $filterParityRates[$parity] = $rates[$arrayKeyHasMinAmount];
        }

        return $filterParityRates;
    }
}
