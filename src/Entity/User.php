<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $name = null;

    #[ORM\Column(length: 320)]
    private ?string $email = null;

    #[ORM\Column(
        options: ['default' => '1'],
    )]
    private ?bool $isEnabled = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $weightKg = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $sizeCm = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $hipMeasurementCm = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $chestMeasurementCm = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $waistMeasurementCm = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $armMeasurementCm = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $legMeasurementCm = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $footMeasurementCm = null;

    #[ORM\Column(options: ['default' => '0'])]
    private ?bool $isFake = null;

    #[ORM\Column(
        type: Types::BOOLEAN,
        columnDefinition: "BOOLEAN NOT NULL GENERATED ALWAYS AS (size_cm IS NOT NULL AND weight_kg IS NOT NULL AND hip_measurement_cm IS NOT NULL AND chest_measurement_cm IS NOT NULL AND waist_measurement_cm IS NOT NULL AND arm_measurement_cm IS NOT NULL AND leg_measurement_cm IS NOT NULL AND foot_measurement_cm IS NOT NULL) STORED",
        insertable: false,
        updatable: false,
        generated: "ALWAYS",
    )]
    private ?bool $hasFinishedOnboarding = null;

    /**
     * @var Collection<int, ClothingList>
     */
    #[ORM\OneToMany(targetEntity: ClothingList::class, mappedBy: 'creator', orphanRemoval: true)]
    private Collection $clothingLists;

    public function __construct()
    {
        $this->clothingLists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function isEnabled(): ?bool
    {
        return $this->isEnabled;
    }

    public function setEnabled(bool $isEnabled): static
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    public function getWeightKg(): ?int
    {
        return $this->weightKg;
    }

    public function setWeightKg(?int $weightKg): static
    {
        $this->weightKg = $weightKg;

        return $this;
    }

    public function getSizeCm(): ?int
    {
        return $this->sizeCm;
    }

    public function setSizeCm(?int $sizeCm): static
    {
        $this->sizeCm = $sizeCm;

        return $this;
    }

    public function getHipMeasurementCm(): ?int
    {
        return $this->hipMeasurementCm;
    }

    public function setHipMeasurementCm(?int $hipMeasurementCm): static
    {
        $this->hipMeasurementCm = $hipMeasurementCm;

        return $this;
    }

    public function getChestMeasurementCm(): ?int
    {
        return $this->chestMeasurementCm;
    }

    public function setChestMeasurementCm(?int $chestMeasurementCm): static
    {
        $this->chestMeasurementCm = $chestMeasurementCm;

        return $this;
    }

    public function getWaistMeasurementCm(): ?int
    {
        return $this->waistMeasurementCm;
    }

    public function setWaistMeasurementCm(?int $waistMeasurementCm): static
    {
        $this->waistMeasurementCm = $waistMeasurementCm;

        return $this;
    }

    public function getArmMeasurementCm(): ?int
    {
        return $this->armMeasurementCm;
    }

    public function setArmMeasurementCm(?int $armMeasurementCm): static
    {
        $this->armMeasurementCm = $armMeasurementCm;

        return $this;
    }

    public function getLegMeasurementCm(): ?int
    {
        return $this->legMeasurementCm;
    }

    public function setLegMeasurementCm(?int $legMeasurementCm): static
    {
        $this->legMeasurementCm = $legMeasurementCm;

        return $this;
    }

    public function getFootMeasurementCm(): ?int
    {
        return $this->footMeasurementCm;
    }

    public function setFootMeasurementCm(?int $footMeasurementCm): static
    {
        $this->footMeasurementCm = $footMeasurementCm;

        return $this;
    }

    public function isFake(): ?bool
    {
        return $this->isFake;
    }

    public function setFake(bool $isFake): static
    {
        $this->isFake = $isFake;

        return $this;
    }

    public function hasFinishedOnboarding(): ?bool
    {
        return $this->hasFinishedOnboarding;
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
            $clothingList->setCreator($this);
        }

        return $this;
    }

    public function removeClothingList(ClothingList $clothingList): static
    {
        if ($this->clothingLists->removeElement($clothingList)) {
            // set the owning side to null (unless already changed)
            if ($clothingList->getCreator() === $this) {
                $clothingList->setCreator(null);
            }
        }

        return $this;
    }
}
