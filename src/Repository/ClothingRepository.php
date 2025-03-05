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

    public function searchByText(string $query): array
    {
        try {
            $words = array_filter(explode(' ', trim($query)));
            
            $qb = $this->createQueryBuilder('c')
                ->select('c.id', 'c.name', 'c.type', 'c.imageUrl', 'c.description');
            
            if (!empty($words)) {
                $conditions = [];
                foreach ($words as $index => $word) {
                    $nameParam = 'name_' . $index;
                    $typeParam = 'type_' . $index;
                    $colorParam = 'color_' . $index;
                    
                    $conditions[] = $qb->expr()->orX(
                        $qb->expr()->like('LOWER(c.name)', ':' . $nameParam),
                        $qb->expr()->like('LOWER(c.type)', ':' . $typeParam),
                        $qb->expr()->like('LOWER(c.color)', ':' . $colorParam)
                    );
                    
                    $qb->setParameter($nameParam, '%' . strtolower($word) . '%')
                    ->setParameter($typeParam, '%' . strtolower($word) . '%')
                    ->setParameter($colorParam, '%' . strtolower($word) . '%');
                }
                
                $qb->where($qb->expr()->andX(...$conditions));
            }
            
            return $qb->getQuery()->getResult();
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
