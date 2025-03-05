<?php

namespace App\Entity;

use App\Repository\ClothingLinkRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: ClothingLinkRepository::class)]
class ClothingLink implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'links')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Clothing $clothing = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $url = null;

    /**
     * @var Collection<int, ClothingPrice>
     */
    #[ORM\OneToMany(targetEntity: ClothingPrice::class, mappedBy: 'link', orphanRemoval: true)]
    private Collection $prices;

    public function __construct()
    {
        $this->prices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClothing(): ?Clothing
    {
        return $this->clothing;
    }

    public function setClothing(?Clothing $clothing): static
    {
        $this->clothing = $clothing;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return Collection<int, ClothingPrice>
     */
    public function getPrices(): Collection
    {
        return $this->prices;
    }

    public function addPrice(ClothingPrice $price): static
    {
        if (!$this->prices->contains($price)) {
            $this->prices->add($price);
            $price->setLink($this);
        }

        return $this;
    }

    public function removePrice(ClothingPrice $price): static
    {
        if ($this->prices->removeElement($price)) {
            // set the owning side to null (unless already changed)
            if ($price->getLink() === $this) {
                $price->setLink(null);
            }
        }

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'url' => $this->url,
            'prices' => array_map(fn (ClothingPrice $price) => $price->jsonSerialize(), $this->prices->toArray()),
        ];
    }
}
