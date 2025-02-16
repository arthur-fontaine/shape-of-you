<?php

namespace App\Repository;

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
    public function removeClothing(int $clothinId, int $bookmarkId): void
    {
        $bookmark = $this->find($bookmarkId);
        $clothing = $bookmark->getClothings()->filter(fn($clothing) => $clothing->getId() === $clothinId)->first();
        $bookmark->removeClothing($clothing);
        $this->getEntityManager()->flush();
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

    public function delete(int $bookmarkId): void
    {
        $bookmark = $this->find($bookmarkId);
        $this->getEntityManager()->remove($bookmark);
        $this->getEntityManager()->flush();
    }
}
