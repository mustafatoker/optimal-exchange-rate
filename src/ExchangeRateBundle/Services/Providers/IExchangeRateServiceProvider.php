<?php

namespace ExchangeRateBundle\Services\Providers;

interface IExchangeRateServiceProvider
{
    /**
     * Fetch the currencies.
     *
     * @return array
     */
    public function fetchCurrencies();

    /**
     * Gets the provider name of exchange rate service provider.
     *
     * @return string
     */
    public function getProviderName();

    /**
     * Send request to the service.
     *
     * @return ResponseInterface
     *
     */
    public function sendRequest();
}