<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\MaterielRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\ExistsFilter;
use App\Repository\TypeRepository;
use App\State\TypeStateProcessor;
use ApiPlatform\Metadata\Post;
use Symfony\Component\Security\Core\Type\PasswordAuthenticatedTypeInterface;
use Symfony\Component\Security\Core\Type\TypeInterface;

#[ORM\Entity(repositoryClass: MaterielRepository::class)]
#[ApiResource(paginationItemsPerPage: 40,
operations:[new Get (normalizationContext:['groups' => 'materiel:items']),
    new GetCollection(normalizationContext: ['groups' => 'materiel:list']),
    ])]
class Materiel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['materiel:list', 'materiel:items'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['materiel:list', 'materiel:items'])]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['materiel:list', 'materiel:items'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['materiel:list', 'materiel:items'])]
    private ?int $quantite = null;

    #[ORM\Column(length: 255)]
    #[Groups(['materiel:list', 'materiel:items'])]
    private ?string $etat = null;

    #[ORM\ManyToOne(inversedBy: 'materiel')]
    #[Groups(['materiel:list', 'materiel:items'])]
    private ?Categorie $categorie = null;

    /**
     * @var Collection<int, Retour>
     */
    #[ORM\OneToMany(targetEntity: Retour::class, mappedBy: 'materiel')]
    private Collection $retour;

    /**
     * @var Collection<int, Emprunt>
     */
    #[ORM\OneToMany(targetEntity: Emprunt::class, mappedBy: 'materiel')]
    private Collection $emprunt;

    

 

    public function __construct()
    {
        $this->retour = new ArrayCollection();
        $this->emprunt = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, Retour>
     */
    public function getRetour(): Collection
    {
        return $this->retour;
    }

    public function addRetour(Retour $retour): static
    {
        if (!$this->retour->contains($retour)) {
            $this->retour->add($retour);
            $retour->setMateriel($this);
        }

        return $this;
    }

    public function removeRetour(Retour $retour): static
    {
        if ($this->retour->removeElement($retour)) {
            // set the owning side to null (unless already changed)
            if ($retour->getMateriel() === $this) {
                $retour->setMateriel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Emprunt>
     */
    public function getEmprunt(): Collection
    {
        return $this->emprunt;
    }

    public function addEmprunt(Emprunt $emprunt): static
    {
        if (!$this->emprunt->contains($emprunt)) {
            $this->emprunt->add($emprunt);
            $emprunt->setMateriel($this);
        }

        return $this;
    }

    public function removeEmprunt(Emprunt $emprunt): static
    {
        if ($this->emprunt->removeElement($emprunt)) {
            // set the owning side to null (unless already changed)
            if ($emprunt->getMateriel() === $this) {
                $emprunt->setMateriel(null);
            }
        }

        return $this;
    }

}
