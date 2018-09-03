<?php

namespace ExchangeRateBundle\Services;

use ExchangeRateBundle\Exceptions\CurrencyNotFound;

final class ExchangeRateMapper
{
    /**
     * @var null|self
     */
    protected static $instance = null;

    /**
     * @var array
     */
    private static $rates = [];

    /**
     * @var string
     */
    private static $currency;

    /**
     * @var string
     */
    private static $exchangeRateProvider;

    /**
     * @return self
     */
    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    /**
     * Call this method to get singleton instance.
     *
     * @return static
     */
    public static function instance()
    {
        if ((! isset(static::$instance))) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * Set the exchange rate provider.
     *
     * @param $exchangeRateProvider
     * @return $this
     */
    public function provider($exchangeRateProvider = null)
    {
        self::$exchangeRateProvider = $exchangeRateProvider;

        return $this;
    }

    /**
     * Set the currency
     *
     * @param $currency
     * @return $this
     */
    public function currency($currency)
    {
        self::$currency = $currency;

        return $this;
    }

    /**
     * Adds the rate to currency.
     *
     * @param $rate
     * @return array
     *
     * @throws CurrencyNotFound
     */
    public function rate($rate = null)
    {
        if (is_null(self::$currency) || trim(self::$currency) === '') {
            throw new CurrencyNotFound;
        }

        return self::$rates[self::$currency][] = [
            'amount' => $rate,
            'provider' => self::$exchangeRateProvider
        ];
    }

    /**
     * @return array
     */
    public function getRates()
    {
        return self::$rates;
    }
}
