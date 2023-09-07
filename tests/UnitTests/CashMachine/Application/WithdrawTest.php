<?php

namespace Tests\CashMachine\Application;

use App\CashMachine\Application\DepositNotes;
use App\CashMachine\Application\Withdraw;
use App\CashMachine\Domain\CashMachine;
use App\Note\Application\Exceptions\NotEnoughNotesException;
use App\Note\Application\Exceptions\NoteUnavailableException;
use App\Note\Domain\Note;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class WithdrawTest extends KernelTestCase
{
    private CashMachine $cashMachine;
    private Withdraw $withdrawService;
    private DepositNotes $depositNotesService;


    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();

        $this->withdrawService = $container->get(Withdraw::class);
        $this->depositNotesService = $container->get(DepositNotes::class);
    }

    public function testValidWithdrawWithDateDuringWeek(): void
    {
        $this->cashMachine = $this->prepareCashMachine(new CashMachine());
        $result = $this->withdrawService->__invoke(30, '2023-09-07', $this->cashMachine);

        $this->assertEquals(
            [new Note(20), new Note(10)],
            $result
        );
    }

    public function testValidWithdrawWithDateInWeekend(): void
    {
        $this->cashMachine = $this->prepareCashMachine(new CashMachine());
        $result = $this->withdrawService->__invoke(30, '2023-09-09', $this->cashMachine);

        $this->assertEquals(
            [new Note(10), new Note(10), new Note(10)],
            $result
        );
    }

    private function prepareCashMachine(CashMachine $cashMachine): CashMachine
    {
        $this->depositNotesService->__invoke($cashMachine, 10, 10);
        $this->depositNotesService->__invoke($cashMachine, 20, 10);
        $this->depositNotesService->__invoke($cashMachine, 50, 10);
        $this->depositNotesService->__invoke($cashMachine, 100, 10);

        return $cashMachine;
    }
}
