<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\Column(length: 280, nullable: true)]
    private ?string $text = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $mediaUrls = null;

    /**
     * @var Collection<int, Clothing>
     */
    #[ORM\ManyToMany(targetEntity: Clothing::class, inversedBy: 'postsFeaturedOn')]
    private Collection $featuredClothings;

    #[ORM\Column(
        options: ['default' => 'CURRENT_TIMESTAMP'],
        updatable: false,
    )]
    private ?\DateTimeImmutable $postedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $modifiedAt = null;

    /**
     * @var Collection<int, PostRate>
     */
    #[ORM\OneToMany(targetEntity: PostRate::class, mappedBy: 'post', orphanRemoval: true, cascade: ['persist'])]
    private Collection $rates;

    public function __construct()
    {
        $this->featuredClothings = new ArrayCollection();
        $this->rates = new ArrayCollection();
        $this->setPostedAt(new \DateTimeImmutable());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getMediaUrls(): ?array
    {
        return $this->mediaUrls;
    }

    public function setMediaUrls(?array $mediaUrls): static
    {
        $this->mediaUrls = $mediaUrls;

        return $this;
    }

    /**
     * @return Collection<int, Clothing>
     */
    public function getFeaturedClothings(): Collection
    {
        return $this->featuredClothings;
    }

    public function addFeaturedClothing(Clothing $featuredClothing): static
    {
        if (!$this->featuredClothings->contains($featuredClothing)) {
            $this->featuredClothings->add($featuredClothing);
        }

        return $this;
    }

    public function removeFeaturedClothing(Clothing $featuredClothing): static
    {
        $this->featuredClothings->removeElement($featuredClothing);

        return $this;
    }

    public function getPostedAt(): ?\DateTimeImmutable
    {
        return $this->postedAt;
    }

    public function setPostedAt(\DateTimeImmutable $postedAt): static
    {
        $this->postedAt = $postedAt;

        return $this;
    }

    public function getModifiedAt(): ?\DateTimeImmutable
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(?\DateTimeImmutable $modifiedAt): static
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    /**
     * @return Collection<int, PostRate>
     */
    public function getRates(): Collection
    {
        return $this->rates;
    }

    public function addRate(PostRate $rate): static
    {
        if (!$this->rates->contains($rate)) {
            $this->rates->add($rate);
            $rate->setPost($this);
        }

        return $this;
    }

    public function removeRate(PostRate $rate): static
    {
        if ($this->rates->removeElement($rate)) {
            // set the owning side to null (unless already changed)
            if ($rate->getPost() === $this) {
                $rate->setPost(null);
            }
        }

        return $this;
    }

    public function jsonSerialize(?User $currentUser = null): array
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'mediaUrls' => $this->mediaUrls,
            'createdAt' => $this->postedAt->format(\DateTime::ATOM),
            'authorId' => $this->author?->getId(),
            'isMyPost' => $currentUser !== null && $this->author !== null && $this->author->getId() === $currentUser->getId()
        ];
    }
}
