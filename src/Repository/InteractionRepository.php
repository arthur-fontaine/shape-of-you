<?php

namespace App\Repository;

use App\Entity\Brand;
use App\Entity\Interaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;


/**
 * @extends ServiceEntityRepository<Interaction>
 */
class InteractionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Interaction::class);
    }

    //    /**
    //     * @return Interaction[] Returns an array of Interaction objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Interaction
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function findPageViewByBrand(Brand $brand)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT
                COUNT(i.id)         AS interactionNb
            FROM interaction i
            WHERE i.interaction->>\'brand\' = :brand
            AND i.interaction->>\'type\' = \'pageView\'
        ';

        $stmt = $conn->prepare($sql);
        $stmt->bindValue('brand', $brand->getName());
        $result = $stmt->executeQuery();
        return array_map(function ($row) {
            return $row;
        }, $result->fetchAllAssociative());
    }

    public function save(Interaction $interaction): void
    {
        $this->getEntityManager()->persist($interaction);
        $this->getEntityManager()->flush();
    }
}
