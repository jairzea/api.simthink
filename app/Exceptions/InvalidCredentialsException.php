<?php

namespace App\Exceptions;

class InvalidCredentialsException extends SimthinkException
{
    public function __construct()
    {
        parent::__construct('Credenciales inválidas. Verifica tu correo y contraseña.', 401);
    }
}