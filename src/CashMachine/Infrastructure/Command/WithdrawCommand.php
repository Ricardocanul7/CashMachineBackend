<?php

namespace App\CashMachine\Infrastructure\Command;

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
    protected function configure(): void
    {
        $this
            ->addArgument(
                'amount',
                InputArgument::REQUIRED
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /**
         * TODO: place logic here
         */
        return Command::SUCCESS;
    }
}