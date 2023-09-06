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

    public function testValidWithdrawWithAmount30(): void
    {
        $this->cashMachine = $this->prepareCashMachine(new CashMachine());
        $result = $this->withdrawService->__invoke(30, $this->cashMachine);

        $this->assertEquals(
            [new Note(20), new Note(10)],
            $result
        );
    }

    public function testValidWithdrawWithAmount80(): void
    {
        $this->cashMachine = $this->prepareCashMachine(new CashMachine());
        $result = $this->withdrawService->__invoke(80, $this->cashMachine);

        $this->assertEquals(
            [new Note(50), new Note(20), new Note(10)],
            $result
        );
    }

    public function testValidWithdrawWithAmount1200(): void
    {
        $this->cashMachine = $this->prepareCashMachine(new CashMachine());
        $result = $this->withdrawService->__invoke(1200, $this->cashMachine);

        $this->assertEquals(
            [
                new Note(100),
                new Note(100),
                new Note(100),
                new Note(100),
                new Note(100),
                new Note(100),
                new Note(100),
                new Note(100),
                new Note(100),
                new Note(100),
                new Note(50),
                new Note(50),
                new Note(50),
                new Note(50),
            ],
            $result
        );
    }

    public function testValidWithdrawWithAmount1210(): void
    {
        $this->cashMachine = $this->prepareCashMachine(new CashMachine());
        $result = $this->withdrawService->__invoke(1210, $this->cashMachine);

        $this->assertEquals(
            [
                new Note(100),
                new Note(100),
                new Note(100),
                new Note(100),
                new Note(100),
                new Note(100),
                new Note(100),
                new Note(100),
                new Note(100),
                new Note(100),
                new Note(50),
                new Note(50),
                new Note(50),
                new Note(50),
                new Note(10),
            ],
            $result
        );
    }

    public function testUnavailableNoteWithdrawWithAmount125(): void
    {
        $this->cashMachine = $this->prepareCashMachine(new CashMachine());

        $this->expectException(NoteUnavailableException::class);
        $this->withdrawService->__invoke(125, $this->cashMachine);
    }

    public function testInvalidWithdrawWithAmountMinus130(): void
    {
        $this->cashMachine = $this->prepareCashMachine(new CashMachine());

        $this->expectException(\InvalidArgumentException::class);
        $this->withdrawService->__invoke(-130, $this->cashMachine);
    }

    public function testWithdrawWithEmptyAmout(): void
    {
        $this->cashMachine = $this->prepareCashMachine(new CashMachine());
        $resultWithNull = $this->withdrawService->__invoke(null, $this->cashMachine);
        $resultWithZero = $this->withdrawService->__invoke(0, $this->cashMachine);

        $this->assertEmpty($resultWithNull);
        $this->assertEmpty($resultWithZero);
    }

    public function testWithdrawWithNotEnoughNotes(): void
    {
        $this->cashMachine = $this->prepareCashMachine(new CashMachine());

        $this->expectException(NotEnoughNotesException::class);
        $this->withdrawService->__invoke(3000, $this->cashMachine);
    }

    public function testCashMachineReducingNumberOfNotes(): void
    {
        $this->cashMachine = $this->prepareCashMachine(new CashMachine());
        $result = $this->withdrawService->__invoke(1000, $this->cashMachine);

        $this->assertEquals(
            [
                new Note(100),
                new Note(100),
                new Note(100),
                new Note(100),
                new Note(100),
                new Note(100),
                new Note(100),
                new Note(100),
                new Note(100),
                new Note(100),
            ],
            $result
        );

        $result = $this->withdrawService->__invoke(500, $this->cashMachine);

        $this->assertEquals(
            [
                new Note(50),
                new Note(50),
                new Note(50),
                new Note(50),
                new Note(50),
                new Note(50),
                new Note(50),
                new Note(50),
                new Note(50),
                new Note(50),
            ],
            $result
        );

        $this->expectException(NotEnoughNotesException::class);
        $result = $this->withdrawService->__invoke(500, $this->cashMachine);
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
