<?php
namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Enseignant; // Importing Enseignant entity
use App\Entity\Promotion; // Importing Promotion entity

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[ApiResource]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    // Change roles to a single string field instead of an array
    #[ORM\Column(length: 255)]
    private ?string $roles = 'ROLE_USER';  // Default role is ROLE_USER

    #[ORM\Column]
    private ?string $password = null;

    // One-to-One relationship with Enseignant entity
    #[ORM\OneToOne(targetEntity: Enseignant::class, cascade: ['persist', 'remove'])]
    private ?Enseignant $enseignant = null;

    // Many-to-One relationship with Promotion entity
    #[ORM\ManyToOne(targetEntity: Promotion::class)]
    #[ORM\JoinColumn(nullable: true)]  // Promotion can be null if it's not set
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

    // A visual identifier that represents this user.
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        // Return an array of roles
        return [$this->roles];
    }

    public function setRoles(string $roles): static
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

    // Getters and setters for the Enseignant relationship
    public function getEnseignant(): ?Enseignant
    {
        return $this->enseignant;
    }

    public function setEnseignant(?Enseignant $enseignant): static
    {
        $this->enseignant = $enseignant;
        return $this;
    }

    // Getters and setters for the Promotion relationship
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
        // If you store any temporary, sensitive data on the user, clear it here
    }
}
