<?php

namespace App\Entity;

use App\Repository\MatiereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: MatiereRepository::class)]
#[ApiResource]
class Matiere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $code_mat = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_matiere = null;

    /**
     * @var Collection<int, Promotion>
     */
    #[ORM\ManyToMany(targetEntity: Promotion::class, mappedBy: 'matieres')]
    private Collection $promotions;

    /**
     * @var Collection<int, Enseignant>
     */
    #[ORM\ManyToMany(targetEntity: Enseignant::class, inversedBy: 'matieres')]
    private Collection $enseignant;

    public function __construct()
    {
        $this->promotions = new ArrayCollection();
        $this->enseignant = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeMat(): ?string
    {
        return $this->code_mat;
    }

    public function setCodeMat(string $code_mat): static
    {
        $this->code_mat = $code_mat;

        return $this;
    }

    public function getNomMatiere(): ?string
    {
        return $this->nom_matiere;
    }

    public function setNomMatiere(string $nom_matiere): static
    {
        $this->nom_matiere = $nom_matiere;

        return $this;
    }

    /**
     * @return Collection<int, Promotion>
     */
    public function getPromotions(): Collection
    {
        return $this->promotions;
    }

    public function addPromotion(Promotion $promotion): static
    {
        if (!$this->promotions->contains($promotion)) {
            $this->promotions->add($promotion);
            $promotion->addMatiere($this);
        }

        return $this;
    }

    public function removePromotion(Promotion $promotion): static
    {
        if ($this->promotions->removeElement($promotion)) {
            $promotion->removeMatiere($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Enseignant>
     */
    public function getEnseignant(): Collection
    {
        return $this->enseignant;
    }

    public function addEnseignant(Enseignant $enseignant): static
    {
        if (!$this->enseignant->contains($enseignant)) {
            $this->enseignant->add($enseignant);
        }

        return $this;
    }

    public function removeEnseignant(Enseignant $enseignant): static
    {
        $this->enseignant->removeElement($enseignant);

        return $this;
    }
}
