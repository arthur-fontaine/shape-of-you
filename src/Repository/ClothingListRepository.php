<?php

namespace App\Repository;

use App\Entity\Clothing;
use App\Entity\ClothingList;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * @extends ServiceEntityRepository<ClothingList>
 */
class ClothingListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClothingList::class);
    }

    //    /**
    //     * @return ClothingList[] Returns an array of ClothingList objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ClothingList
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function removeClothing(int $clothingId, int $bookmarkId, int $userId): void
    {
        $bookmark = $this->find($bookmarkId);
        if ($bookmark->getCreator()->getId() !== $userId) {
            return;
        }
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'DELETE FROM clothing_list_clothing WHERE clothing_list_id = :bookmarkId AND clothing_id = :clothingId';
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery(['bookmarkId' => $bookmarkId, 'clothingId' => $clothingId]);
    }

    public function create(User $user, string $name, bool $isBookmark): ClothingList
    {
        $clothingList = new ClothingList();
        $clothingList->setCreator($user);
        $clothingList->setName($name);
        $clothingList->setBookmarkList($isBookmark);

        $this->getEntityManager()->persist($clothingList);
        $this->getEntityManager()->flush();

        return $clothingList;
    }

    public function delete(int $bookmarkId, int $userId): void
    {
        $this->createQueryBuilder('c')
            ->delete()
            ->where('c.id = :id')
            ->andWhere('c.creator = :creator')
            ->setParameter('id', $bookmarkId)
            ->setParameter('creator', $userId)
            ->getQuery()
            ->execute();
    }

    public function addClothing(int $clothingId, int $bookmarkId, int $userId)
    {
        $bookmark = $this->find($bookmarkId);
        if ($bookmark->getCreator()->getId() !== $userId) {
            return;
        }
        $clothing = $this->getEntityManager()->getRepository(Clothing::class)->find($clothingId);
        $bookmark->addClothing($clothing);
        $this->getEntityManager()->flush();

        return $bookmark;
    }
}
