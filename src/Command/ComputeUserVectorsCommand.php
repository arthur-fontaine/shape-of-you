<?php

namespace App\Command;

use App\Message\SyncUserVectorsMessage;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:compute-user-vectors',
    description: 'Trigger computing vectors for users.'
)]
class ComputeUserVectorsCommand extends Command
{
    public function __construct(private MessageBusInterface $bus)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Trigger computing vectors for users.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $this->bus->dispatch(new SyncUserVectorsMessage());

        $io->success('User vectors have been successfully synchronized.');

        return Command::SUCCESS;
    }
}
