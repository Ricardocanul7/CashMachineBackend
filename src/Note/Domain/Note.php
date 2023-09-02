<?php

namespace App\Note\Domain;

use NoteUnavailableException;

class Note {
    private int $value;
    const AVAILABLE_NOTES = [10, 20, 50, 100];

    public function __construct(int $value) 
    {
        if(self::checkAvailableValue($value)){
            $this->$value = $value;
        }

        throw new NoteUnavailableException();
    }


    /**
     * Get the value of value
     */ 
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set the value of value
     *
     * @return  self
     */ 
    public function setValue($value)
    {
        if(self::checkAvailableValue($value)){
            $this->$value = $value;

            return $this;
        }

        throw new NoteUnavailableException();
    }

    public static function checkAvailableValue($value): bool
    {
        return in_array($value, self::AVAILABLE_NOTES);
    }
}