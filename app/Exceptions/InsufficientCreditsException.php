<?php

namespace App\Exceptions;

use DomainException;

class InsufficientCreditsException extends DomainException
{
    public function __construct(string $message = 'El usuario no tiene créditos suficientes.')
    {
        parent::__construct($message);
    }
}