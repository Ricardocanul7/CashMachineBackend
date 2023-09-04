<?php

namespace App\CashMachine\Application;

use App\CashMachine\Domain\CashMachine;
use App\Note\Domain\Factory\NoteFactory;
use App\Note\Domain\Note;
use App\Note\Infrastructure\Exceptions\NoteUnavailableException;
use Exception;

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
        //$this->prepareCashMachine();
        $availableNotes = Note::AVAILABLE_NOTES;
        rsort($availableNotes, SORT_REGULAR);

        $remainder = $amount;
        $notesToWithdraw = [];

        foreach ($availableNotes as $key => $noteVal) {
            if ($amount >= $noteVal) {
                $notesQuantity = intdiv($remainder, $noteVal);

                $notesToWithdraw = array_merge(
                    $notesToWithdraw,
                    $this->noteFactory->create($noteVal, $notesQuantity)
                );

                $notesMod = $remainder % $noteVal;
                $remainder -= $notesQuantity * $noteVal;
            }

            if ($key === count($availableNotes) - 1) {
                if ($notesMod !== 0) {
                    throw new NoteUnavailableException();
                }
            }
        }

        // TODO: try to remove the notes from the cash machine or get an exception which will be catch at command level
        // return $this->cashMachine->withdrawNotes($notesToWithdraw);

        return $notesToWithdraw;
    }

    // Temporal method to fill the cash machine
    private function prepareCashMachine(): void
    {
        $noteAmountPerValue = 10;

        foreach (Note::AVAILABLE_NOTES as $noteValue) {
            $notes = $this->noteFactory->create($noteValue, $noteAmountPerValue);
            $this->cashMachine->addNotes($notes);
        }
    }
}
