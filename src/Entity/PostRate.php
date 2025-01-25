<?php

namespace App\Entity;

use App\Repository\PostRateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRateRepository::class)]
class PostRate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'postRates')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $rater = null;

    #[ORM\ManyToOne(inversedBy: 'rates')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Post $post = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $rate10 = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRater(): ?User
    {
        return $this->rater;
    }

    public function setRater(?User $rater): static
    {
        $this->rater = $rater;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): static
    {
        $this->post = $post;

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
}
