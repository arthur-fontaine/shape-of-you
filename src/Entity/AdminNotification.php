<?php

namespace App\Entity;

use App\Repository\AdminNotificationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdminNotificationRepository::class)]
class AdminNotification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(options: ['jsonb' => true])]
    private array $notification = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNotification(): array
    {
        return $this->notification;
    }

    public function setNotification(array $notification): static
    {
        $this->notification = $notification;

        return $this;
    }
}
