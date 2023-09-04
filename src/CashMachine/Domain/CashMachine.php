<?php

namespace App\CashMachine\Domain;

use App\Note\Domain\Note;
use App\Note\Infrastructure\Exceptions\NoteUnavailableException;

class CashMachine
{
    /** @var Note[] $notes */
    private array $notes;

    public function addNote(Note $note): void
    {
        $this->notes[] = $note;
    }

    public function addNotes(array $notes): void
    {
        foreach ($notes as $note) {
            $this->notes[] = $note;
        }
    }

    /**
     * Get the value of notes
     * 
     * @return Note[]
     */
    public function getNotes(): array
    {
        return $this->notes;
    }

    /**
     * Remove available notes in ATM, if not available notes, throw an exception
     * if the operation is successful return the original notes to withdraw
     */
    public function withdrawNotes(array $notes): array
    {
        // TODO: Put logic here
        return [];
    }
}
