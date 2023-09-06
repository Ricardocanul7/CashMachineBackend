<?php

namespace Tests\CashMachine\Application;

use App\CashMachine\Application\DepositNotes;
use App\CashMachine\Domain\CashMachine;
use App\Note\Domain\Note;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DepositNotesTest extends KernelTestCase
{
    private DepositNotes $depositNotesService;
    
    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();

        $this->depositNotesService = $container->get(DepositNotes::class);
    }

    public function testNoteDepositWith5NotesOf100(): void
    {
        $cashMachine = new CashMachine();
        $this->depositNotesService->__invoke($cashMachine, 100, 5);

        $this->assertEquals(
            [
                new Note(100),
                new Note(100),
                new Note(100),
                new Note(100),
                new Note(100)
            ],
            $cashMachine->getNotes()
        );
    }

    public function testNoteDepositWith5NotesOf100And2NotesOf20(): void
    {
        $cashMachine = new CashMachine();
        $this->depositNotesService->__invoke($cashMachine, 100, 5);
        $this->depositNotesService->__invoke($cashMachine, 20, 2);

        $this->assertEquals(
            [
                new Note(100),
                new Note(100),
                new Note(100),
                new Note(100),
                new Note(100),
                new Note(20),
                new Note(20),
            ],
            $cashMachine->getNotes()
        );
    }
}