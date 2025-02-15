<?php

namespace App\MessageHandler;

use App\Message\CalculateUserRecommendationsMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class CalculateUserRecommendationsMessageHandler
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function __invoke(CalculateUserRecommendationsMessage $message): void
    {
        $this->entityManager->getConnection()->executeQuery('CALL calculate_user_hybrid_recommendations()');
    }
}
