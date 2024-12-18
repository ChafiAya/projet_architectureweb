<?php

namespace App\Entity;

use App\Repository\PromotionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PromotionRepository::class)]
class Promotion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $niveau_promotion = null;

    #[ORM\Column(length: 255)]
    private ?string $enseignement = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $choix = null;

    /**
     * @var Collection<int, Matiere>
     */
    #[ORM\ManyToMany(targetEntity: Matiere::class, inversedBy: 'promotions')]
    private Collection $matieres;

    #[ORM\Column]
    private ?int $nbr_etudiants = null;

    /**
     * @var Collection<int, Reserve>
     */
    #[ORM\ManyToMany(targetEntity: Reserve::class, mappedBy: 'promotion')]
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

    public function getNiveauPromotion(): ?string
    {
        return $this->niveau_promotion;
    }

    public function setNiveauPromotion(string $niveau_promotion): static
    {
        $this->niveau_promotion = $niveau_promotion;

        return $this;
    }

    public function getEnseignement(): ?string
    {
        return $this->enseignement;
    }

    public function setEnseignement(string $enseignement): static
    {
        $this->enseignement = $enseignement;

        return $this;
    }

    public function getChoix(): ?string
    {
        return $this->choix;
    }

    public function setChoix(?string $choix): static
    {
        $this->choix = $choix;

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
        }

        return $this;
    }

    public function removeMatiere(Matiere $matiere): static
    {
        $this->matieres->removeElement($matiere);

        return $this;
    }

    public function getNbrEtudiant(): ?int
    {
        return $this->nbr_etudiants;
    }

    public function setNbrEtudiant(int $nbr_etudiant): static
    {
        $this->nbr_etudiants = $nbr_etudiant;

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
            $reserf->addPromotion($this);
        }

        return $this;
    }

    public function removeReserf(Reserve $reserf): static
    {
        if ($this->reserves->removeElement($reserf)) {
            $reserf->removePromotion($this);
        }

        return $this;
    }
}
