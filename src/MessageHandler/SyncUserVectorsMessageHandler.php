<?php

namespace App\MessageHandler;

use App\Message\SyncUserVectorsMessage;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class SyncUserVectorsMessageHandler
{
    private function __construct(private EntityManagerInterface $entityManager) {}

    public function __invoke(SyncUserVectorsMessage $message): void
    {
        $this->entityManager->getConnection()->executeQuery('CALL sync_user_vectors()');
    }
}
