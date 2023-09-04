<?php

namespace App\Note\Infrastructure\Exceptions;

use Exception;

class NotEnoughNotesException extends Exception
{
    public function __construct()
    {
        parent::__construct(
            'Not enough notes'
        );
    }
}
