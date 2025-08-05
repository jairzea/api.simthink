<?php

namespace App\Exceptions;

use Exception;

class SimthinkException extends Exception
{
    public function __construct(string $message = "Error interno", int $code = 500)
    {
        parent::__construct($message, $code);
    }
}