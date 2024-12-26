<?php

namespace App\Entity;

use App\Repository\MatiereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MatiereRepository::class)]
class Matiere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 55)]
    private ?string $nom = null;

    #[ORM\Column(length: 55, unique: true)]
    private ?string $codeMatiere = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, Enseignant>
     */
    #[ORM\OneToMany(targetEntity: Enseignant::class, mappedBy: 'id_matiere')]
    private Collection $id_enseignant;

    public function __construct()
    {
        $this->id_enseignant = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCodeMatiere(): ?string
    {
        return $this->codeMatiere;
    }

    public function setCodeMatiere(string $codeMatiere): self
    {
        $this->codeMatiere = $codeMatiere;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Enseignant>
     */
    public function getIdEnseignant(): Collection
    {
        return $this->id_enseignant;
    }

    public function addIdEnseignant(Enseignant $idEnseignant): static
    {
        if (!$this->id_enseignant->contains($idEnseignant)) {
            $this->id_enseignant->add($idEnseignant);
            $idEnseignant->setIdMatiere($this);
        }

        return $this;
    }

    public function removeIdEnseignant(Enseignant $idEnseignant): static
    {
        if ($this->id_enseignant->removeElement($idEnseignant)) {
            // set the owning side to null (unless already changed)
            if ($idEnseignant->getIdMatiere() === $this) {
                $idEnseignant->setIdMatiere(null);
            }
        }

        return $this;
    }
}
