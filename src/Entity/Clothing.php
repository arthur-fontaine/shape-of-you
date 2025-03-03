<?php

namespace App\Entity;

use JsonSerializable;
use App\Enum\ClothingType;
use App\Enum\Color;
use App\Repository\ClothingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClothingRepository::class)]
class Clothing implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: ClothingType::class)]
    private ?ClothingType $type = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, enumType: Color::class)]
    private array $color = [];

    #[ORM\Column(options: ['jsonb' => true])]
    private array $measurements = [];

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $socialRate5 = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $ecologyRate5 = null;

    /**
     * @var Collection<int, ClothingLink>
     */
    #[ORM\OneToMany(targetEntity: ClothingLink::class, mappedBy: 'clothing', orphanRemoval: true)]
    private Collection $links;

    /**
     * @var Collection<int, ClothingList>
     */
    #[ORM\ManyToMany(targetEntity: ClothingList::class, mappedBy: 'clothings')]
    private Collection $clothingLists;

    /**
     * @var Collection<int, Post>
     */
    #[ORM\ManyToMany(targetEntity: Post::class, mappedBy: 'featuredClothings')]
    private Collection $postsFeaturedOn;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $imageUrl = null;

    /**
     * @var Collection<int, DressingPiece>
     */
    #[ORM\OneToMany(targetEntity: DressingPiece::class, mappedBy: 'clothing')]
    private Collection $dressingPieces;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    public function __construct()
    {
        $this->links = new ArrayCollection();
        $this->clothingLists = new ArrayCollection();
        $this->postsFeaturedOn = new ArrayCollection();
        $this->dressingPieces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?ClothingType
    {
        return $this->type;
    }

    public function setType(ClothingType $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Color[]
     */
    public function getColor(): array
    {
        return $this->color;
    }

    public function setColor(array $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getMeasurements(): array
    {
        return $this->measurements;
    }

    public function setMeasurements(array $measurements): static
    {
        $this->measurements = $measurements;

        return $this;
    }

    public function getSocialRate5(): ?int
    {
        return $this->socialRate5;
    }

    public function setSocialRate5(?int $socialRate5): static
    {
        $this->socialRate5 = $socialRate5;

        return $this;
    }

    public function getEcologyRate5(): ?int
    {
        return $this->ecologyRate5;
    }

    public function setEcologyRate5(?int $ecologyRate5): static
    {
        $this->ecologyRate5 = $ecologyRate5;

        return $this;
    }

    /**
     * @return Collection<int, ClothingLink>
     */
    public function getLinks(): Collection
    {
        return $this->links;
    }

    public function addLink(ClothingLink $link): static
    {
        if (!$this->links->contains($link)) {
            $this->links->add($link);
            $link->setClothing($this);
        }

        return $this;
    }

    public function removeLink(ClothingLink $link): static
    {
        if ($this->links->removeElement($link)) {
            // set the owning side to null (unless already changed)
            if ($link->getClothing() === $this) {
                $link->setClothing(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ClothingList>
     */
    public function getClothingLists(): Collection
    {
        return $this->clothingLists;
    }

    public function addClothingList(ClothingList $clothingList): static
    {
        if (!$this->clothingLists->contains($clothingList)) {
            $this->clothingLists->add($clothingList);
            $clothingList->addClothing($this);
        }

        return $this;
    }

    public function removeClothingList(ClothingList $clothingList): static
    {
        if ($this->clothingLists->removeElement($clothingList)) {
            $clothingList->removeClothing($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPostsFeaturedOn(): Collection
    {
        return $this->postsFeaturedOn;
    }

    public function addPostsFeaturedOn(Post $postsFeaturedOn): static
    {
        if (!$this->postsFeaturedOn->contains($postsFeaturedOn)) {
            $this->postsFeaturedOn->add($postsFeaturedOn);
            $postsFeaturedOn->addFeaturedClothing($this);
        }

        return $this;
    }

    public function removePostsFeaturedOn(Post $postsFeaturedOn): static
    {
        if ($this->postsFeaturedOn->removeElement($postsFeaturedOn)) {
            $postsFeaturedOn->removeFeaturedClothing($this);
        }

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * @return Collection<int, DressingPiece>
     */
    public function getDressingPieces(): Collection
    {
        return $this->dressingPieces;
    }

    public function addDressingPiece(DressingPiece $dressingPiece): static
    {
        if (!$this->dressingPieces->contains($dressingPiece)) {
            $this->dressingPieces->add($dressingPiece);
            $dressingPiece->setClothing($this);
        }

        return $this;
    }

    public function removeDressingPiece(DressingPiece $dressingPiece): static
    {
        if ($this->dressingPieces->removeElement($dressingPiece)) {
            // set the owning side to null (unless already changed)
            if ($dressingPiece->getClothing() === $this) {
                $dressingPiece->setClothing(null);
            }
        }

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

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'color' => $this->color,
            'measurements' => $this->measurements,
            'socialRate5' => $this->socialRate5,
            'ecologyRate5' => $this->ecologyRate5,
            'imageUrl' => $this->imageUrl,
            'name' => $this->name,
        ];
    }
}
