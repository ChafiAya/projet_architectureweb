<?php
namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Enseignant;
use App\Entity\Promotion; 

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'Un compte avec cet email existe déjà.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column(type: 'json')] // Change le type en JSON pour stocker un tableau dans la base de données
    private array $roles = []; // Par défaut, un tableau vide

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    // Ajout du champ typeUtilisateur (étudiant, enseignant, agent universitaire)
    #[ORM\Column(length: 50)]
    private ?string $typeUtilisateur = null;

    // Relation 1-1 avec la table Enseignant
    #[ORM\OneToOne(targetEntity: Enseignant::class, cascade: ['persist', 'remove'])]
    private ?Enseignant $enseignant = null;

    // Relation n-1 la table Promotion
    #[ORM\ManyToOne(targetEntity: Promotion::class)]
    #[ORM\JoinColumn(nullable: true)]  
    private ?Promotion $promotion = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    // Identifiant visuel représentant l'utilisateur
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
{
    // Ajoute toujours le rôle ROLE_USER par défaut
    $roles = $this->roles;
    $roles[] = 'ROLE_USER';
    return array_unique($roles); // Évite les doublons
}

public function setRoles(array $roles): self
{
    $this->roles = $roles;
    return $this;
}
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    // Champ Nom
    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    // Champ Prénom
    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
    }

    // Champ typeUtilisateur
    public function getTypeUtilisateur(): ?string
    {
        return $this->typeUtilisateur;
    }

    public function setTypeUtilisateur(string $typeUtilisateur): static
    {
        $this->typeUtilisateur = $typeUtilisateur;
        return $this;
    }

    // Getter et setter pour la relation Enseignant
    public function getEnseignant(): ?Enseignant
    {
        return $this->enseignant;
    }

    public function setEnseignant(?Enseignant $enseignant): static
    {
        $this->enseignant = $enseignant;
        return $this;
    }

    // Getter et setter pour la relation Promotion
    public function getPromotion(): ?Promotion
    {
        return $this->promotion;
    }

    public function setPromotion(?Promotion $promotion): static
    {
        $this->promotion = $promotion;
        return $this;
    }

    public function eraseCredentials(): void
    {
        // Rien à faire ici pour le moment, mais utile si des données sensibles temporaires sont stockées
    }
}
