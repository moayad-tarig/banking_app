<?php

namespace App\Exceptions;

use Exception;

class InvalidPinLengthException extends Exception
{
    public function __construct()
    {
        parent::__construct('Pin must be 4 digits long');
    }
}
