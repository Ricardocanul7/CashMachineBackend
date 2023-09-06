<?php

namespace App\Note\Application\Exceptions;

class NotEnoughNotesException extends \Exception
{
    public function __construct()
    {
        parent::__construct(
            'Not enough notes'
        );
    }
}
