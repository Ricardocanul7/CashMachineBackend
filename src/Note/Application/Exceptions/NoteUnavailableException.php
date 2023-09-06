<?php

namespace App\Note\Application\Exceptions;

class NoteUnavailableException extends \Exception
{
    public function __construct()
    {
        parent::__construct(
            'Not available notes'
        );
    }
}
