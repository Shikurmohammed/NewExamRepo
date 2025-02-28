<?php

namespace App\Exceptions;

use Exception;

class DatabaseConnectionException extends Exception
{

    public function __construct(
        $message = "We are experiencing technical difficulties. Please try again later.",
        $code = 0,
        Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
