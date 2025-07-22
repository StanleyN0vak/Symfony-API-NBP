<?php

namespace App\Command;

use App\Service\CurrencyService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:sync-currencies',
    description: 'Synchronizes exchange rates from API NBP',
)]
class SyncCurrenciesCommand extends Command
{
    public function __construct(private CurrencyService $currencyService, private EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->currencyService->syncCurrencies($this->em);
        $output->writeln('Currencies synced!');

        return Command::SUCCESS;
    }
}
