<?php

namespace App\CashMachine\Application;

use App\CashMachine\Domain\CashMachine;
use App\Note\Domain\Factory\NoteFactory;

class DepositNotes
{
    public function __construct(
        private NoteFactory $noteFactory,
    ) {
    }

    public function __invoke(CashMachine &$cashMachine, int $noteValue, int $noteCount): void
    {
        $notes = $this->noteFactory->create($noteValue, $noteCount);
        $cashMachine->addNotes($notes);
    }
}
