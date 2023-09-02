<?php

namespace App\CashMachine\Domain;

use App\Note\Domain\Note;

class CashMachine {
    private Note $notes;

    /**
     * @param Note[] $notes
     */
    public function fill(array $notes): void
    {
        $this->notes = $notes;
    }

    public function addNote(Note $note): void
    {
        $this->notes[] = $note;
    }
}