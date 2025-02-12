<?php

namespace App\MessageHandler;

use App\Message\CalculateUserRecommendationsMessage;
use App\Message\SyncUserVectorsMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final class SyncUserVectorsMessageHandler
{
    private function __construct(private EntityManagerInterface $entityManager, private MessageBusInterface $bus) {}

    public function __invoke(SyncUserVectorsMessage $message): void
    {
        $this->entityManager->getConnection()->executeQuery('CALL sync_user_vectors()');
        $this->bus->dispatch(new CalculateUserRecommendationsMessage());
    }
}
