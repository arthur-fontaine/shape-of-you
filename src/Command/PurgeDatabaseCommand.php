<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(
    name: 'app:purge-database',
    description: 'Add a short description for your command',
    aliases: ['app:reset-database'],
)]
class PurgeDatabaseCommand extends Command
{
    public function __construct(private KernelInterface $kernel)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Purge the database');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Purging database');

        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(['command' => 'doctrine:database:drop', '--force' => true]);
        $code = $application->run($input, $output);
        if ($code !== 0) {
            $io->error('Database does not exist');
            return Command::FAILURE;
        }

        $input = new ArrayInput(['command' => 'doctrine:database:create']);
        $code = $application->run($input, $output);
        if ($code !== 0) {
            $io->error('Database could not be created');
            return Command::FAILURE;
        }

        $input = new ArrayInput(['command' => 'doctrine:migrations:migrate', '--no-interaction' => true]);
        $code = $application->run($input, $output);
        if ($code !== 0) {
            $io->error('Migrations could not be executed');
            return Command::FAILURE;
        }

        $io->success('Database purged');

        return Command::SUCCESS;
    }
}
