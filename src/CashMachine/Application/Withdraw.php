<?php

namespace App\CashMachine\Application;

use App\CashMachine\Domain\CashMachine;
use App\Note\Application\Exceptions\NoteUnavailableException;
use App\Note\Domain\Factory\NoteFactory;
use App\Note\Domain\Note;
use InvalidArgumentException;

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

        if($amount < 0){
            throw new InvalidArgumentException();
        }

        $notesToWithdraw = $this->computeNotesToWithdraw($amount);

        return $cashMachine->removeNotes($notesToWithdraw);
    }

    /**
     * @return Note[]
     */
    private function computeNotesToWithdraw(int $amount): array
    {
        $availableNotesValues = Note::AVAILABLE_NOTES;
        rsort($availableNotesValues, SORT_REGULAR);

        $remainder = $amount;
        $notesToWithdraw = [];

        foreach ($availableNotesValues as $key => $noteValue) {
            if ($amount >= $noteValue) {
                $notesQuantity = intdiv($remainder, $noteValue);

                $notesToWithdraw = array_merge(
                    $notesToWithdraw,
                    $this->noteFactory->create($noteValue, $notesQuantity)
                );

                $notesMod = $remainder % $noteValue;
                $remainder -= $notesQuantity * $noteValue;
            }

            if ($key === count($availableNotesValues) - 1) {
                if ($notesMod !== 0) {
                    throw new NoteUnavailableException();
                }
            }
        }

        return $notesToWithdraw;
    }
}
