<?php

namespace App\CashMachine\Domain;

use App\Note\Application\Exceptions\NotEnoughNotesException;
use App\Note\Domain\Note;

class CashMachine
{
    /** @var Note[] */
    private array $notes = [];

    public function addNote(Note $note): void
    {
        $this->notes[] = $note;
    }

    public function addNotes(array $notes): void
    {
        $this->notes = array_merge($this->notes, $notes);
    }

    /**
     * Get the value of notes.
     *
     * @return Note[]
     */
    public function getNotes(): array
    {
        return $this->notes;
    }

    /**
     * @param Note[] $notes
     */
    public function removeNotes(array $notes): array
    {
        $withdrawNotesCount = self::getNoteCount($notes);
        $availableNotesCount = self::getNoteCount($this->notes);

        if (!$this->areEnoughNotesAvailable($withdrawNotesCount, $availableNotesCount)) {
            throw new NotEnoughNotesException();
        }

        $this->persitNotesRemoval($notes);

        return $notes;
    }

    /**
     * @param Note[] $notes
     */
    private function persitNotesRemoval(array $notes): void
    {
        $tempAvailableNotesForRemoval = $this->notes;

        foreach ($notes as $note) {
            $keyRemoval = array_search($note, $tempAvailableNotesForRemoval);
            unset($tempAvailableNotesForRemoval[$keyRemoval]);
        }

        $this->notes = $tempAvailableNotesForRemoval;
    }

    private function areEnoughNotesAvailable(array $withdrawNotesCount, array $availableNotesCount): bool
    {
        foreach ($withdrawNotesCount as $noteValue => $noteCount) {
            if ($availableNotesCount[$noteValue] < $noteCount) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param Note[] $notes
     */
    public static function getNoteCount(array $notes): array
    {
        $noteValues = array_map(fn ($item) => $item->getValue(), $notes);

        return array_count_values($noteValues);
    }
}
