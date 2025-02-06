<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($registry, Post::class);
    }

    /**
     * Create a new post
     * @param User $user
     * @param string $text
     * @param array<string> $mediaUrls
     * @return Post
     */
    public function create(User $user, string $text, array $mediaUrls): Post
    {
        $post = new Post();
        $post->setAuthor($user);
        $post->setText($text);
        $post->setMediaUrls($mediaUrls);

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return $post;
    }
}
