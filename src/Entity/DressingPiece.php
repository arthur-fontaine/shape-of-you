<?php

namespace App\Entity;

use App\Repository\DressingPieceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DressingPieceRepository::class)]
class DressingPiece
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'dressing')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $rate10 = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    #[ORM\ManyToOne(inversedBy: 'dressingPieces')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Clothing $clothing = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getRate10(): ?int
    {
        return $this->rate10;
    }

    public function setRate10(int $rate10): static
    {
        $this->rate10 = $rate10;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
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
}
