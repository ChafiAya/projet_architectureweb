<?php

namespace App\Entity;

use App\Repository\SaleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: SaleRepository::class)]
#[ApiResource]
class Sale
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $batiment = null;

    #[ORM\Column]
    private ?int $etage = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_de_salle = null;

    #[ORM\Column]
    private ?int $capacite = null;

    /**
     * @var Collection<int, Reserve>
     */
    #[ORM\ManyToMany(targetEntity: Reserve::class, mappedBy: 'salles')]
    private Collection $reserves;

    public function __construct()
    {
        $this->reserves = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBatiment(): ?string
    {
        return $this->batiment;
    }

    public function setBatiment(string $batiment): static
    {
        $this->batiment = $batiment;

        return $this;
    }

    public function getEtage(): ?int
    {
        return $this->etage;
    }

    public function setEtage(int $etage): static
    {
        $this->etage = $etage;

        return $this;
    }

    public function getNomDeSalle(): ?string
    {
        return $this->nom_de_salle;
    }

    public function setNomDeSalle(string $nom_de_salle): static
    {
        $this->nom_de_salle = $nom_de_salle;

        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): static
    {
        $this->capacite = $capacite;

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
            $reserf->addSalle($this);
        }

        return $this;
    }

    public function removeReserf(Reserve $reserf): static
    {
        if ($this->reserves->removeElement($reserf)) {
            $reserf->removeSalle($this);
        }

        return $this;
    }

    //fonction pour la gestion de disponibilite de la salle 


    public function isDisponible(\DateTimeInterface $currentDateTime): bool
    {
        //on parcoure toutes les reservation avec la boucle foeach 
        foreach ($this->reserves as $reservation) {
            // si la reservation.dateReservation == la date actuelle et $currentDateTime et entre l'intervale de resercation (debut-fin) alors on retourne false.
            if ($reservation->getDateReservation() == $currentDateTime->format('Y-m-d') &&
                $currentDateTime >= $reservation->getHeureDepart() && 
                $currentDateTime <= $reservation->getHeureFin()) {
                    
                return false;
            }
        }
        // sinon la fonction return true ==> disponible :)

        return true;
    }
}
