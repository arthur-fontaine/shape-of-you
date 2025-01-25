<?php

namespace App\Entity;

use App\Repository\ClothingPriceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClothingPriceRepository::class)]
class ClothingPrice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'prices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ClothingLink $link = null;

    #[ORM\Column]
    private ?int $priceCts = null;

    #[ORM\Column]
    private ?bool $isOnSale = null;

    #[ORM\Column(
        options: ['default' => 'CURRENT_TIMESTAMP'],
    )]
    private ?\DateTimeImmutable $registeredAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLink(): ?ClothingLink
    {
        return $this->link;
    }

    public function setLink(?ClothingLink $link): static
    {
        $this->link = $link;

        return $this;
    }

    public function getPriceCts(): ?int
    {
        return $this->priceCts;
    }

    public function setPriceCts(int $priceCts): static
    {
        $this->priceCts = $priceCts;

        return $this;
    }

    public function isOnSale(): ?bool
    {
        return $this->isOnSale;
    }

    public function setOnSale(bool $isOnSale): static
    {
        $this->isOnSale = $isOnSale;

        return $this;
    }

    public function getRegisteredAt(): ?\DateTimeImmutable
    {
        return $this->registeredAt;
    }

    public function setRegisteredAt(\DateTimeImmutable $registeredAt): static
    {
        $this->registeredAt = $registeredAt;

        return $this;
    }
}
