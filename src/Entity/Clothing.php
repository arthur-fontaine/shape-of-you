<?php

namespace App\Entity;

use App\Enum\ClothingType;
use App\Enum\Color;
use App\Repository\ClothingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClothingRepository::class)]
class Clothing
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
}
