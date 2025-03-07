<?php

namespace App\Repository;

use App\Entity\Clothing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\ClothingLink;
use App\Entity\ClothingPrice;

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

    public function findByPriceRange(int $minPriceCts, int $maxPriceCts): array
    {
        return $this->createQueryBuilder('cp')
            ->andWhere('cp.priceCts >= :minPrice')
            ->andWhere('cp.priceCts <= :maxPrice')
            ->setParameter('minPrice', $minPriceCts)
            ->setParameter('maxPrice', $maxPriceCts)
            ->orderBy('cp.priceCts', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function searchByText(string $query, ?array $colorFilters = null, ?array $typeFilters = null, ?int $priceMin = null, ?int $priceMax = null): array
    {
        try {
            $words = array_filter(explode(' ', trim($query)));
            
            $qb = $this->createQueryBuilder('c')
                ->select('c');
            
            if (!empty($words)) {
                $conditions = [];
                foreach ($words as $index => $word) {
                    $nameParam = 'name_' . $index;
                    $descParam = 'desc_' . $index;
                    
                    $conditions[] = $qb->expr()->orX(
                        $qb->expr()->like('LOWER(c.name)', ':' . $nameParam),
                        $qb->expr()->like('LOWER(c.description)', ':' . $descParam)
                    );
                    
                    $qb->setParameter($nameParam, '%' . strtolower($word) . '%')
                        ->setParameter($descParam, '%' . strtolower($word) . '%');
                }
                
                $qb->andWhere($qb->expr()->andX(...$conditions));
            }
            
            // Apply color filter
            if (!empty($colorFilters)) {
                $colorConditions = [];
                foreach ($colorFilters as $index => $color) {
                    $paramName = 'color_' . $index;
                    // This might need adjustment based on how colors are stored
                    $colorConditions[] = $qb->expr()->like('c.color', ':' . $paramName);
                    $qb->setParameter($paramName, '%' . $color . '%');
                }
                
                if (!empty($colorConditions)) {
                    $qb->andWhere($qb->expr()->orX(...$colorConditions));
                }
            }
            
            if (!empty($typeFilters)) {
                $qb->andWhere('c.type IN (:types)')
                    ->setParameter('types', $typeFilters);
            }
            
            $results = $qb->getQuery()->getResult();
            
            if ($priceMin !== null || $priceMax !== null) {
                $results = array_filter($results, function (Clothing $clothing) use ($priceMin, $priceMax) {
                    $links = $clothing->getLinks();
                    if ($links->isEmpty()) {
                        return false;
                    }
                    
                    foreach ($links as $link) {
                        $prices = $link->getPrices();
                        if ($prices->isEmpty()) {
                            continue;
                        }
                        
                        $latestPrice = $prices->last();
                        $priceCts = $latestPrice->getPriceCts();
                        $priceEuros = $priceCts / 100;
                        
                        $minOk = $priceMin === null || $priceEuros >= $priceMin;
                        $maxOk = $priceMax === null || $priceEuros <= $priceMax;
                        
                        if ($minOk && $maxOk) {
                            return true;
                        }
                    }
                    
                    return false;
                });
            }
            
            return $results;
        } catch (\Exception $e) {
            error_log('Error in searchByText: ' . $e->getMessage());
            throw $e;
        }
    }

    public function findMaxPrice(): int
    {
        $result = $this->createQueryBuilder('c')
            ->select('MAX(cp.priceCts)')
            ->join('c.links', 'cl')
            ->join('cl.prices', 'cp')
            ->getQuery()
            ->getSingleScalarResult();
            
        return (int) (($result ?? 0) / 100 + 1); 
    }
}
