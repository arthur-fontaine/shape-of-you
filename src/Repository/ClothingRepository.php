<?php

namespace App\Repository;

use App\Entity\Clothing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Clothing>
 */
class ClothingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Clothing::class);
    }

    /**
     * @return Clothing[] Returns an array of Clothing objects
     */
    public function findByFields(array $criteria): array
    {
        $qb = $this->createQueryBuilder('c');

        foreach ($criteria as $field => $value) {
            $qb->andWhere("c.$field = :$field")
                ->setParameter($field, $value);
        }

        return $qb->getQuery()->getResult();
    }

    //    public function findOneBySomeField($value): ?Clothing
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
