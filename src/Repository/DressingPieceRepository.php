<?php

namespace App\Repository;

use App\Entity\DressingPiece;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DressingPiece>
 */
class DressingPieceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DressingPiece::class);
    }

//    /**
//     * @return DressingPiece[] Returns an array of DressingPiece objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DressingPiece
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function createDressingPiece(array $data, User $getUser)
    {
        $dressingPiece = new DressingPiece();
        $dressingPiece->setClothing($data['clothing']);
        $this->_em->persist($dressingPiece);
        $this->_em->flush();
        return $dressingPiece;
    }
}
