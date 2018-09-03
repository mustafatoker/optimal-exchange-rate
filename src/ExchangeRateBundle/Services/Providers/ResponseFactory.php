<?php

namespace ExchangeRateBundle\Services\Providers;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class ResponseFactory
{
    /**
     * Converts response into a PSR response.
     *
     * @param int $status
     * @param array $headers
     * @param null $body
     * @param string $version
     *
     * @return ResponseInterface
     */
    public function createResponse($status = 200, array $headers = [], $body = null, $version = '1.1'): ResponseInterface
    {
        return new Response($status, $headers, $body, $version);
    }
}

