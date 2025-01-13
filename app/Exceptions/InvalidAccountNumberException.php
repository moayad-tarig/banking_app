<?php

namespace App\Exceptions;

use Exception;

class InvalidAccountNumberException extends Exception
{
    public function __construct($message = "Invalid account number", $code = 400)
    {
        parent::__construct($message, $code);
    }
}
