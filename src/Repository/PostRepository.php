<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    public function delete(int $postId, int $userId): bool
    {
        $post = $this->findOneBy(['id' => $postId, 'author' => $userId]);

        if ($post) {
            foreach ($post->getRates() as $rate) {
                $this->entityManager->remove($rate);
            }
            $this->entityManager->remove($post);
            $this->entityManager->flush();
            return true;
        }

        return false;
    }

    /**
     * Find all posts of my friends
     * @param User $user
     * @return Post[]
     */
    public function findFriendsPosts(User $user): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT
                p.id         AS postId,
                p.text       AS post,
                p.media_urls AS medias,
                p.author_id  AS authorId,
                a.name       AS authorName,
                pr.rate10    AS myRate
            FROM post p
            INNER JOIN public.user_friend f
                ON (
                     (f.user_source = :userId AND p.author_id = f.user_target)
                     OR
                     (f.user_target = :userId AND p.author_id = f.user_source)
                   )
            LEFT JOIN public.post_rate pr
                ON pr.post_id = p.id AND pr.rater_id = :userId
            JOIN public.user a
                ON a.id = p.author_id
            WHERE p.author_id <> :userId
        ';

        $stmt = $conn->prepare($sql);
        $stmt->bindValue('userId', $user->getId());
        $result = $stmt->executeQuery();

        return array_map(function ($row) {
            $post = [];
            $post['postId'] = $row['postid'];
            $post['text'] = $row['post'];
            $post['mediaUrls'] = json_decode($row['medias'], true);
            $post['authorId'] = $row['authorid'];
            $post['authorName'] = $row['authorname'];
            $post['myRate'] = $row['myrate'];
            return $post;
        }, $result->fetchAllAssociative());
    }

    public function deleteWithoutUser(Post $post): void
    {
        $this->entityManager->remove($post);
        $this->entityManager->flush();
    }
}
