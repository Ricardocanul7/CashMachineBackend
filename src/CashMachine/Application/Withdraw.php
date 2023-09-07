<?php

namespace App\CashMachine\Application;

use App\CashMachine\Domain\CashMachine;
use App\Note\Application\Exceptions\NotEnoughNotesException;
use App\Note\Application\Exceptions\NoteUnavailableException;
use App\Note\Domain\Factory\NoteFactory;
use App\Note\Domain\Note;
use DateTime;

class Withdraw
{
    public function __construct(
        private NoteFactory $noteFactory,
    ) {
    }

    /**
     * @return Note[]
     */
    public function __invoke(?int $amount, string $day, CashMachine $cashMachine): array
    {
        if (empty($amount)) {
            return [];
        }

        if ($amount < 0) {
            throw new \InvalidArgumentException();
        }

        $notesToWithdraw = $this->computeNotesToWithdraw($amount, $day, $cashMachine);

        return $cashMachine->removeNotes($notesToWithdraw);
    }

    /**
     * @return Note[]
     */
    private function computeNotesToWithdraw(int $amount, string $date, CashMachine $cashMachine): array
    {
        $availableNoteCount = CashMachine::getNoteCount($cashMachine->getNotes());
        $date = DateTime::createFromFormat('Y-m-d', $date);
        $this->orderByDayOfTheWeek($date->format('w'), $availableNoteCount);
        // krsort($availableNoteCount);

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



    public function orderByDayOfTheWeek(int $day, &$noteCount): void
    {
        switch($day){
            case 0: 
                arsort($noteCount);
                break;
            case 1:
                krsort($noteCount);
                break;
            case 2:
                krsort($noteCount);
                break;
            case 3:
                krsort($noteCount);
                break;
            case 4:
                krsort($noteCount);
                break;
            case 5:
                krsort($noteCount);
                break;
            case 6:
                arsort($noteCount);
                break;
            default:
                // Throw exception NonExistatAvailableDay
        }
    }
}
