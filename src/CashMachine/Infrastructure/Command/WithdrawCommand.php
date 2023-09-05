<?php

namespace App\CashMachine\Infrastructure\Command;

use App\CashMachine\Application\DepositNotes;
use App\CashMachine\Application\Withdraw;
use App\CashMachine\Domain\CashMachine;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'cash-machine:withdraw',
    description: 'Withdraw notes from the cash machine in amounts of $ 10, $ 20, $ 50 and $ 100',
    hidden: false
)]
class WithdrawCommand extends Command
{
    public function __construct(
        private Withdraw $withdrawService,
        private DepositNotes $depositNotesService,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                'amount',
                InputArgument::REQUIRED
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $emptyCashMachine = new CashMachine();
        $readyCashMachine = $this->prepareCashMachine($emptyCashMachine);

        try {
            $result = $this->withdrawService->__invoke($input->getArgument('amount'), $readyCashMachine);
            $output->writeln(json_encode($result));
        } catch (Exception $e) {
            $output->writeln($e->getMessage());
            return Command::FAILURE;
        }


        return Command::SUCCESS;
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
