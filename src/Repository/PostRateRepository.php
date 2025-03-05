<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\PostRate;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PostRate>
 */
class PostRateRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($registry, PostRate::class);
    }

    public function ratePost(Post $post, User $user, int $rate): void
    {
        if ($rate < 0 || $rate > 10) {
            throw new \InvalidArgumentException('Rate must be between 0 and 10');
        }

        $postRate = $this->findOneBy(['post' => $post, 'rater' => $user]);

        if ($postRate === null) {
            $postRate = new PostRate();
            $postRate->setPost($post);
            $postRate->setRater($user);
        }

        $postRate->setRate10($rate);

        $this->entityManager->persist($postRate);
        $this->entityManager->flush();
    }
}
