<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, \JsonSerializable
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

    /**
     * @var Collection<int, DressingPiece>
     */
    #[ORM\OneToMany(targetEntity: DressingPiece::class, mappedBy: 'owner', orphanRemoval: true)]
    private Collection $dressing;

    /**
     * @var Collection<int, Post>
     */
    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'author', orphanRemoval: true)]
    private Collection $posts;

    /**
     * @var Collection<int, PostRate>
     */
    #[ORM\OneToMany(targetEntity: PostRate::class, mappedBy: 'rater', orphanRemoval: true)]
    private Collection $postRates;

    /**
     * @var Collection<int, self>
     */
    #[JoinTable(name: 'user_friend')]
    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'friends')]
    private Collection $friends;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $password = null;

    #[ORM\Column]
    private bool $isVerified = false;

    public function __construct()
    {
        $this->clothingLists = new ArrayCollection();
        $this->dressing = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->postRates = new ArrayCollection();
        $this->friends = new ArrayCollection();
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

    /**
     * @return Collection<int, DressingPiece>
     */
    public function getDressing(): Collection
    {
        return $this->dressing;
    }

    public function addDressing(DressingPiece $dressing): static
    {
        if (!$this->dressing->contains($dressing)) {
            $this->dressing->add($dressing);
            $dressing->setOwner($this);
        }

        return $this;
    }

    public function removeDressing(DressingPiece $dressing): static
    {
        if ($this->dressing->removeElement($dressing)) {
            // set the owning side to null (unless already changed)
            if ($dressing->getOwner() === $this) {
                $dressing->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setAuthor($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getAuthor() === $this) {
                $post->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PostRate>
     */
    public function getPostRates(): Collection
    {
        return $this->postRates;
    }

    public function addPostRate(PostRate $postRate): static
    {
        if (!$this->postRates->contains($postRate)) {
            $this->postRates->add($postRate);
            $postRate->setRater($this);
        }

        return $this;
    }

    public function removePostRate(PostRate $postRate): static
    {
        if ($this->postRates->removeElement($postRate)) {
            // set the owning side to null (unless already changed)
            if ($postRate->getRater() === $this) {
                $postRate->setRater(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getFriends(): Collection
    {
        return $this->friends;
    }

    public function addFriend(self $friend): static
    {
        if (!$this->friends->contains($friend)) {
            $this->friends->add($friend);
        }

        return $this;
    }

    public function removeFriend(self $friend): static
    {
        $this->friends->removeElement($friend);

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'friends' => $this->getFriends()->toArray(),
            'posts' => $this->getPosts()->toArray(),
        ];
    }
}
