<?php

namespace App\Repository;

use App\Entity\Clothing;
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
    public function upsertDressingPiece(array $data, User $user): DressingPiece
    {
        $clothing = $this->getEntityManager()->getRepository(Clothing::class)->find($data['clothingId']);
        $dressingPiece = $this->findOneBy(['clothing' => $clothing, 'owner' => $user]);

        if (!$dressingPiece) {
            $dressingPiece = new DressingPiece();
            $dressingPiece->setClothing($clothing);
            $dressingPiece->setOwner($user);
        }

        if (isset($data['comment'])) {
            $dressingPiece->setComment($data['comment']);
        }
        if (isset($data['rate'])) {
            $dressingPiece->setRate10($data['rate']);
        }
        $this->getEntityManager()->persist($dressingPiece);
        $this->getEntityManager()->flush();
        return $dressingPiece;
    }

    public function removeDressingPiece(int $clothingId, User $user): void
    {
        $dressingPiece = $this->findOneBy(['clothing' => $clothingId, 'owner' => $user->getId()]);
        if ($dressingPiece) {
            $this->getEntityManager()->remove($dressingPiece);
            $this->getEntityManager()->flush();
        }
    }

}
