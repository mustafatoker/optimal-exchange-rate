<?php

namespace ExchangeRateBundle\Services;

use ExchangeRateBundle\Services\Providers\IExchangeRateServiceProvider;

final class ExchangeRates
{
    /**
     * The providers to be fetched.
     *
     * @var array
     */
    protected $exchangeRateProviders;

    /**
     * Adds the provider for being fetch.
     *
     * @param $provider
     *
     * @return void
     */
    public function addProvider(IExchangeRateServiceProvider $provider)
    {
        $this->exchangeRateProviders[] = $provider;
    }

    /**
     * Fetch the currency rates.
     *
     * @return array
     * @throws \ExchangeRateBundle\Exceptions\CurrencyNotFound
     */
    public function fetchCurrencyRates()
    {
        /** @var IExchangeRateServiceProvider $exchangeRateProvider */
        foreach ($this->exchangeRateProviders as $exchangeRateProvider) {
            $currencies = $exchangeRateProvider->fetchCurrencies();

            foreach ($currencies as $currency) {
                ExchangeRateMapper::getInstance()->provider($exchangeRateProvider->getProviderName())->currency($currency['symbol'])->rate($currency['amount']);
            }
        }

        return ExchangeRateMapper::getInstance()->getRates();
    }
}
