<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserClothingRecommendation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserClothingRecommendation>
 */
class UserClothingRecommendationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserClothingRecommendation::class);
    }

       /**
        * @return UserClothingRecommendation[] Returns an array of UserClothingRecommendation objects
        */
       public function findUserRecommendations(User $user): array
       {
           return $this->createQueryBuilder('u')
                ->andWhere('u.owner = :user')
                ->setParameter('user', $user)
               ->getQuery()
               ->getResult()
           ;
       }

    //    public function findOneBySomeField($value): ?UserClothingRecommendation
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
