<?php

namespace App\CashMachine\Application;

use App\CashMachine\Domain\CashMachine;
use App\Note\Application\Exceptions\NotEnoughNotesException;
use App\Note\Application\Exceptions\NoteUnavailableException;
use App\Note\Domain\Factory\NoteFactory;
use App\Note\Domain\Note;

class Withdraw
{
    public function __construct(
        private NoteFactory $noteFactory,
    ) {
    }

    /**
     * @return Note[]
     */
    public function __invoke(?int $amount, CashMachine $cashMachine): array
    {
        if (empty($amount)) {
            return [];
        }

        if ($amount < 0) {
            throw new \InvalidArgumentException();
        }

        $notesToWithdraw = $this->computeNotesToWithdraw($amount, $cashMachine);

        return $cashMachine->removeNotes($notesToWithdraw);
    }

    /**
     * @return Note[]
     */
    private function computeNotesToWithdraw(int $amount, CashMachine $cashMachine): array
    {
        $availableNoteCount = CashMachine::getNoteCount($cashMachine->getNotes());
        krsort($availableNoteCount);

        $notesToWithdraw = [];
        $remainder = $amount;
        $lastNoteValue = 0;

        foreach ($availableNoteCount as $noteValue => $noteCount) {
            for ($i = 0; $i < $noteCount; ++$i) {
                if ($remainder >= $noteValue) {
                    $remainder -= $noteValue;
                    $notesToWithdraw[] = new Note($noteValue);
                }
            }
            $lastNoteValue = $noteValue;
        }

        if ($remainder > 0) {
            if ($remainder > $lastNoteValue) {
                throw new NotEnoughNotesException();
            }
            throw new NoteUnavailableException();
        }

        return $notesToWithdraw;
    }
}
