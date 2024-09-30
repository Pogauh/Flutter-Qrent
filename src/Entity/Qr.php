<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\QrRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QrRepository::class)]
#[ApiResource]
class Qr
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
