<?php

namespace App\CashMachine\Application;

use App\CashMachine\Domain\CashMachine;
use App\Note\Domain\Factory\NoteFactory;
use App\Note\Domain\Note;
use App\Note\Infrastructure\Exceptions\NoteUnavailableException;

class Withdraw
{
    public function __construct(
        private CashMachine $cashMachine,
        private NoteFactory $noteFactory,
    ) {
    }

    /**
     * @return Note[]
     */
    public function __invoke(int $amount): array
    {
        $this->prepareCashMachine();

        if(empty($amount)){
            return [];
        }

        $notesToWithdraw = $this->computeNotesToWithdraw($amount);

        return $this->cashMachine->removeNotes($notesToWithdraw);
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

    // Temporal method to fill the cash machine
    private function prepareCashMachine(): void
    {
        $noteAmountPerValue = 10;

        foreach (Note::AVAILABLE_NOTES as $noteValueue) {
            $notes = $this->noteFactory->create($noteValueue, $noteAmountPerValue);
            $this->cashMachine->addNotes($notes);
        }
    }
}
