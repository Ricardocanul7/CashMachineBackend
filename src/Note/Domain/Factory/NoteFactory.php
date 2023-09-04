<?php

namespace App\Note\Domain\Factory;

use App\Note\Domain\Note;
use App\Note\Infrastructure\Exceptions\NoteUnavailableException;

class NoteFactory
{
    /**
     * @return Note[]
     */
    public function create($noteValue, $noteQuantity): array
    {
        $notes = [];

        if (!in_array($noteValue, Note::AVAILABLE_NOTES)) {
            throw new NoteUnavailableException();
        }

        for ($i = 0; $i < $noteQuantity; $i++) {
            $notes[] = new Note($noteValue);
        }

        return $notes;
    }
}
