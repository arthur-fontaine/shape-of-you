<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    public function searchByText(string $query): array
    {
        try {
            $words = array_filter(explode(' ', trim($query)));
            
            $qb = $this->createQueryBuilder('u')
                ->select('u.id', 'u.name', 'u.email');
            
            if (!empty($words)) {
                $conditions = [];
                foreach ($words as $index => $word) {
                    $nameParam = 'name_' . $index;
                    $emailParam = 'email_' . $index;
                    
                    $conditions[] = $qb->expr()->orX(
                        $qb->expr()->like('LOWER(u.name)', ':' . $nameParam),
                        $qb->expr()->like('LOWER(u.email)', ':' . $emailParam)
                    );
                    
                    $qb->setParameter($nameParam, '%' . strtolower($word) . '%')
                    ->setParameter($emailParam, '%' . strtolower($word) . '%');
                }
                
                $qb->where($qb->expr()->andX(...$conditions))
                ->andWhere('u.isFake = false')
                ->andWhere('u.isEnabled = true');
            }
            
            return $qb->getQuery()->getResult();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function delete(User $user): void
    {
        $this->find($user->getId());
        $this->getEntityManager()->remove($user);
        $this->getEntityManager()->flush();
    }
}
