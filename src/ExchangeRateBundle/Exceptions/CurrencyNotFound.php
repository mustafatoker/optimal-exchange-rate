<?php

namespace ExchangeRateBundle\Exceptions;

use Exception;
use Throwable;

class CurrencyNotFound extends Exception
{
    /**
     * The default exception message.
     *
     * @var string
     */
    protected $message = "Currency not found";

    /**
     * The custom exception message.
     *
     * @var string
     */
    protected $customMessage = "Currency '%s' not found";

    /**
     * CurrencyNotFound constructor.
     *
     * @param string $currency
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($currency = null, $code = 0, Throwable $previous = null)
    {
        $message = (!is_null($currency)) ? sprintf($this->customMessage, $currency) : $this->message;

        parent::__construct($message, $code, $previous);
    }
}