<?php

namespace App\Entity;

use App\Repository\ClothingListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClothingListRepository::class)]
class ClothingList implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'clothingLists')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $creator = null;

    /**
     * @var Collection<int, Clothing>
     */
    #[ORM\ManyToMany(targetEntity: Clothing::class, inversedBy: 'clothingLists', orphanRemoval: true)]
    private Collection $clothings;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(options: ['default' => '0'])]
    private ?bool $isBookmarkList = null;

    public function __construct()
    {
        $this->clothings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): static
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * @return Collection<int, Clothing>
     */
    public function getClothings(): Collection
    {
        return $this->clothings;
    }

    public function addClothing(Clothing $clothing): static
    {
        if (!$this->clothings->contains($clothing)) {
            $this->clothings->add($clothing);
        }

        return $this;
    }

    public function removeClothing(Clothing $clothing): static
    {
        $this->clothings->removeElement($clothing);

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function isBookmarkList(): ?bool
    {
        return $this->isBookmarkList;
    }

    public function setBookmarkList(bool $isBookmarkList): static
    {
        $this->isBookmarkList = $isBookmarkList;

        return $this;
    }


    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'isBookmarkList' => $this->isBookmarkList,
        ];
    }
}
