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

    public function toArray(Clothing $clothing): array
    {
        return [
            'id' => $clothing->getId(),
            'name' => $clothing->getName(),
            'type' => $clothing->getType()->value,
            'imageUrl' => $clothing->getImageUrl(),
            'color' => $clothing->getColor(),
            'socialRate5' => $clothing->getSocialRate5(),
            'ecologyRate5' => $clothing->getEcologyRate5(),
            'measurements' => $clothing->getMeasurements() 
        ];
    }
}
