<?php

namespace App\Entity;
use App\Repository\EnseignantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EnseignantRepository::class)]
class Enseignant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 55)]
    private ?string $Nom = null;

    #[ORM\Column(length: 55)]
    private ?string $Prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $Email = null;

    #[ORM\ManyToOne(inversedBy: 'enseignants')]
    private ?Matiere $id_matiere = null;

    /**
     * @var Collection<int, Reserve>
     */
    #[ORM\ManyToMany(targetEntity: Reserve::class, mappedBy: 'enseignants')]
    private Collection $reserves;

    public function __construct()
    {
        $this->reserves = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): static
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): static
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): static
    {
        $this->Email = $Email;

        return $this;
    }

    public function getIdMatiere(): ?Matiere
    {
        return $this->id_matiere;
    }

    public function setIdMatiere(?Matiere $id_matiere): static
    {
        $this->id_matiere = $id_matiere;

        return $this;
    }

    /**
     * @return Collection<int, Reserve>
     */
    public function getReserves(): Collection
    {
        return $this->reserves;
    }

    public function addReserve(Reserve $reserve): static
    {
        if (!$this->reserves->contains($reserve)) {
            $this->reserves->add($reserve);
            $reserve->addEnseignant($this);  
        }

        return $this;
    }

    public function removeReserve(Reserve $reserve): static
    {
        if ($this->reserves->removeElement($reserve)) {
            $reserve->removeEnseignant($this);  
        }

        return $this;
    }
}
