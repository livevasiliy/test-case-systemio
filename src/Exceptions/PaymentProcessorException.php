<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class PaymentProcessorException extends Exception
{
    public function __construct(string $message = "Couldn't handle payment request", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}