<?php

namespace App\Entity;

use App\Repository\InteractionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InteractionRepository::class)]
class Interaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $originUser = null;

    #[ORM\Column(options: ['jsonb' => true])]
    private array $interaction = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOriginUser(): ?User
    {
        return $this->originUser;
    }

    public function setOriginUser(?User $originUser): static
    {
        $this->originUser = $originUser;

        return $this;
    }

    public function getInteraction(): array
    {
        return $this->interaction;
    }

    public function setInteraction(array $interaction): static
    {
        $this->interaction = $interaction;

        return $this;
    }
}
