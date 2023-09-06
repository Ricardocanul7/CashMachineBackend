<?php

namespace App\Note\Domain\Factory;

use App\Note\Application\Exceptions\NoteUnavailableException;
use App\Note\Domain\Note;

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

        for ($i = 0; $i < $noteQuantity; ++$i) {
            $notes[] = new Note($noteValue);
        }

        return $notes;
    }
}
