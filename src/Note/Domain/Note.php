<?php

namespace App\Note\Domain;

use App\Note\Application\Exceptions\NoteUnavailableException;
use JsonSerializable;

class Note implements JsonSerializable
{
    private int $value;
    const AVAILABLE_NOTES = [10, 20, 50, 100];

    public function __construct(int $value = NULL)
    {
        if(empty($value)){
            throw new NoteUnavailableException();
        }

        if (!self::checkAvailableValue($value)) {
            throw new NoteUnavailableException();
        }

        $this->value = $value;
    }


    /**
     * Get the value of value
     */
    public function getValue(): int
    {
        return $this->value;
    }

    public static function checkAvailableValue($value): bool
    {
        return in_array($value, self::AVAILABLE_NOTES);
    }

    public function __toString(): string
    {
        return strval($this->value);
    }

    public function jsonSerialize(): mixed
    {
        return $this->value;
    }
}
