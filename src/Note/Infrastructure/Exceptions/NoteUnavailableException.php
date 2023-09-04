<?php

namespace App\Note\Infrastructure\Exceptions;

use Exception;

class NoteUnavailableException extends Exception
{
    public function __construct()
    {
        parent::__construct(
            'Not available notes'
        );
    }
}
