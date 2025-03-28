<?php

namespace App\Entity;

use App\Repository\BrandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BrandRepository::class)]
class Brand
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Clothing>
     */
    #[ORM\OneToMany(targetEntity: Clothing::class, mappedBy: 'brand')]
    private Collection $clothing;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'brand')]
    private Collection $users;

    public function __construct()
    {
        $this->clothing = new ArrayCollection();
        $this->users = new ArrayCollection();
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

    /**
     * @return Collection<int, Clothing>
     */
    public function getClothing(): Collection
    {
        return $this->clothing;
    }

    public function addClothing(Clothing $clothing): static
    {
        if (!$this->clothing->contains($clothing)) {
            $this->clothing->add($clothing);
            $clothing->setBrand($this);
        }

        return $this;
    }

    public function removeClothing(Clothing $clothing): static
    {
        if ($this->clothing->removeElement($clothing)) {
            // set the owning side to null (unless already changed)
            if ($clothing->getBrand() === $this) {
                $clothing->setBrand(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setBrand($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getBrand() === $this) {
                $user->setBrand(null);
            }
        }

        return $this;
    }
}
