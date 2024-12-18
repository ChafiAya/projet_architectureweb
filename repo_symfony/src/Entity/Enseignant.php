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

    #[ORM\Column(length: 255)]
    private ?string $nom_enseignant = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $email_enseignant = null;

    #[ORM\Column(length: 255)]
    private ?string $departement = null;

    /**
     * @var Collection<int, Matiere>
     */
    #[ORM\ManyToMany(targetEntity: Matiere::class, mappedBy: 'enseignant')]
    private Collection $matieres;

    /**
     * @var Collection<int, Reserve>
     */
    #[ORM\ManyToMany(targetEntity: Reserve::class, mappedBy: 'enseignants')]
    private Collection $reserves;

    public function __construct()
    {
        $this->matieres = new ArrayCollection();
        $this->reserves = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEnseignant(): ?string
    {
        return $this->nom_enseignant;
    }

    public function setNomEnseignant(string $nom_enseignant): static
    {
        $this->nom_enseignant = $nom_enseignant;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmailEnseignant(): ?string
    {
        return $this->email_enseignant;
    }

    public function setEmailEnseignant(string $email_enseignant): static
    {
        $this->email_enseignant = $email_enseignant;

        return $this;
    }

    public function getDepartement(): ?string
    {
        return $this->departement;
    }

    public function setDepartement(string $departement): static
    {
        $this->departement = $departement;

        return $this;
    }

    /**
     * @return Collection<int, Matiere>
     */
    public function getMatieres(): Collection
    {
        return $this->matieres;
    }

    public function addMatiere(Matiere $matiere): static
    {
        if (!$this->matieres->contains($matiere)) {
            $this->matieres->add($matiere);
            $matiere->addEnseignant($this);
        }

        return $this;
    }

    public function removeMatiere(Matiere $matiere): static
    {
        if ($this->matieres->removeElement($matiere)) {
            $matiere->removeEnseignant($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Reserve>
     */
    public function getReserves(): Collection
    {
        return $this->reserves;
    }

    public function addReserf(Reserve $reserf): static
    {
        if (!$this->reserves->contains($reserf)) {
            $this->reserves->add($reserf);
            $reserf->addEnseignant($this);
        }

        return $this;
    }

    public function removeReserf(Reserve $reserf): static
    {
        if ($this->reserves->removeElement($reserf)) {
            $reserf->removeEnseignant($this);
        }

        return $this;
    }
   
}
