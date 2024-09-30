<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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


#[ORM\Entity(repositoryClass: CategorieRepository::class)]
#[ApiResource(paginationItemsPerPage: 40,
operations:[new Get (normalizationContext:['groups' => 'categorie:items']),
    new GetCollection(normalizationContext: ['groups' => 'categorie:list']),
    new Post(processor: UserStateProcessor::class),
    new Patch (security: "is_granted ('ROLE_ADMIN') or object == categorie")])]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    #[Groups(['categorie:list', 'categorie:items'])]
    private ?string $nom = null;

    /**
     * @var Collection<int, Materiel>
     */
    #[ORM\OneToMany(targetEntity: Materiel::class, mappedBy: 'categorie')]
    private Collection $materiel;


    public function __construct()
    {
        $this->materiel = new ArrayCollection();
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

    /**
     * @return Collection<int, Materiel>
     */
    public function getMateriel(): Collection
    {
        return $this->materiel;
    }

    public function addMateriel(Materiel $materiel): static
    {
        if (!$this->materiel->contains($materiel)) {
            $this->materiel->add($materiel);
            $materiel->setCategorie($this);
        }

        return $this;
    }

    public function removeMateriel(Materiel $materiel): static
    {
        if ($this->materiel->removeElement($materiel)) {
            // set the owning side to null (unless already changed)
            if ($materiel->getCategorie() === $this) {
                $materiel->setCategorie(null);
            }
        }

        return $this;
    }

   

}