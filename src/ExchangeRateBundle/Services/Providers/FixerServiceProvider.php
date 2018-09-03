<?php

namespace ExchangeRateBundle\Services\Providers;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use ExchangeRateBundle\Exceptions\ResponseException;

class FixerServiceProvider implements IExchangeRateServiceProvider
{
    /**
     * Endpoint of the Fixer API
     *
     * @var string
     */
    private $endpoint;

    /**
     * @var \GuzzleHttp\ClientInterface
     */
    private $client;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * FixerProvider constructor.
     *
     * @param null $endpoint
     * @param Client $client
     * @param ResponseFactory|null $responseFactory
     */
    public function __construct($endpoint = null, Client $client, ResponseFactory $responseFactory = null)
    {
        $this->endpoint        = $endpoint;
        $this->client          = $client;
        $this->responseFactory = $responseFactory;
    }

    /**
     * Gets the provider name of exchange rate service provider.
     *
     * @return string
     */
    public function getProviderName()
    {
        return 'fixer';
    }

    /**
     * Fetch the currencies.
     *
     * @return array
     * @throws \Exception
     */
    public function fetchCurrencies()
    {
        $response = $this->sendRequest();

        return $this->readResponseBodyString($response->getBody()->getContents());
    }

    /**
     * Send request to the service.
     *
     * @return ResponseInterface
     *
     */
    public function sendRequest()
    {
        try {
            $response = $this->getResponse($this->client->get($this->endpoint));

        } catch (\Exception $e) {
            throw new \RuntimeException("Something went wrong while fetching currency data from Fixer provider.");
        }

        return $response;

    }

    /**
     * Converts response into a PSR response.
     *
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     */
    private function getResponse(ResponseInterface $response)
    {
        $body = $response->getBody()->getContents();

        return $this->responseFactory->createResponse(
            $response->getStatusCode(),
            $response->getHeaders(),
            $body,
            $response->getProtocolVersion()
        );
    }

    /**
     * Read the message response body string.
     *
     * @param $responseBodyString
     *
     * @return array
     * @throws \Exception
     */
    private function readResponseBodyString($responseBodyString)
    {
        $responseBody = json_decode($responseBodyString, true);

        if ((! isset($responseBody['result']))) {
            throw new ResponseException('Response body is malformed.');
        }

        return $responseBody['result'];
    }
}