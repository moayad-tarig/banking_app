<?php

namespace App\Exceptions;

use Exception;

class DepositAmountToLowException extends Exception
{
    public function __construct($minimum_depoit)
    {
        $this->message = 'Deposit amount must be greater than ' . $minimum_depoit;
    }
}
