<?php

namespace App\MessageHandler;

use App\Message\CreateAiFunctionsAndProceduresInDbMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class CreateAiFunctionsAndProceduresInDbMessageHandler
{
    public function __construct(private EntityManagerInterface $entityManager) {
    }

    public function __invoke(CreateAiFunctionsAndProceduresInDbMessage $message): void
    {
        $directory = __DIR__ . '/../../database';
        $files = glob($directory .'/**/*.sql');

        $connection = $this->entityManager->getConnection();

        foreach ($files as $file) {
          $sql = file_get_contents($file);
          $connection->executeStatement($sql);
        }
    }
}
