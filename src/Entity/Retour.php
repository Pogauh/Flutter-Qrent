<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\RetourRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RetourRepository::class)]
#[ApiResource]
class Retour
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateRetour = null;

    #[ORM\Column(length: 50)]
    private ?string $etatMateriel = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateRetour(): ?\DateTimeInterface
    {
        return $this->dateRetour;
    }

    public function setDateRetour(\DateTimeInterface $dateRetour): static
    {
        $this->dateRetour = $dateRetour;

        return $this;
    }

    public function getEtatMateriel(): ?string
    {
        return $this->etatMateriel;
    }

    public function setEtatMateriel(string $etatMateriel): static
    {
        $this->etatMateriel = $etatMateriel;

        return $this;
    }
}
