<?php

namespace App\CashMachine\Infrastructure\Command;

use App\CashMachine\Application\Withdraw;
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
    private $withdrawService;

    public function __construct(Withdraw $withdrawService)
    {
        parent::__construct();
        $this->withdrawService = $withdrawService;
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
        try{
            $result = $this->withdrawService->__invoke($input->getArgument('amount'));
            $output->writeln(json_encode($result));
        }catch(Exception $e){
            $output->writeln($e->getMessage());
            return Command::FAILURE;
        }
        

        return Command::SUCCESS;
    }
}
