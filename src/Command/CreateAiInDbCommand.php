<?php

namespace App\Command;

use App\Message\CreateAiFunctionsAndProceduresInDbMessage;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:create-ai-in-db',
    description: 'Add AI functions and procedures to the database.'
)]
class CreateAiInDbCommand extends Command
{
    public function __construct(private MessageBusInterface $bus)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Add AI functions and procedures to the database.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $this->bus->dispatch(new CreateAiFunctionsAndProceduresInDbMessage());

        $io->success('AI functions and procedures have been successfully added to the database.');

        return Command::SUCCESS;
    }
}
