<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]  // Escaped for reserved SQL word "user"
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    // Primary key for the User entity, auto-generated by Doctrine
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // User's name, with a maximum length of 50 characters
    #[ORM\Column(length: 50)]
    private ?string $name = null;

    // User's email, with a maximum length of 180 characters, unique for each user
    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    // Roles assigned to the user
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    // Hashed password for the user
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var Collection<int, Workout>
     */
    // One-to-many relationship with Workout entities, mapped by the "person" property in Workout
    #[ORM\OneToMany(targetEntity: Workout::class, mappedBy: 'person', orphanRemoval: true)]
    private Collection $workouts;

    // Many-to-one relationship with the Image entity (optional)
    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Image $image = null;

    // Constructor to initialize the $workouts collection as an ArrayCollection
    public function __construct()
    {
        $this->workouts = new ArrayCollection();
    }

    // Getter for the ID
    public function getId(): ?int
    {
        return $this->id;
    }

    // Getter and setter for the user's name
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    // Getter and setter for the user's email
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user (usually the email).
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    // Returns the roles assigned to the user. Guarantees that every user has at least the ROLE_USER.
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';  // Ensure every user has ROLE_USER

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    // Setter for roles
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    // Getter for the hashed password
    public function getPassword(): string
    {
        return $this->password;
    }

    // Setter for the password
    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    // Used to erase credentials if necessary (e.g., plain passwords)
    public function eraseCredentials(): void
    {
        // Erase temporary, sensitive data here if needed (e.g., $this->plainPassword = null;)
    }

    /**
     * @return Collection<int, Workout>
     */
    // Getter for the collection of workouts associated with this user
    public function getWorkouts(): Collection
    {
        return $this->workouts;
    }

    // Adds a workout to the user's workout collection and sets the user as the owner
    public function addWorkout(Workout $workout): static
    {
        if (!$this->workouts->contains($workout)) {
            $this->workouts->add($workout);
            $workout->setPerson($this);
        }

        return $this;
    }

    // Removes a workout from the user's workout collection and resets the association
    public function removeWorkout(Workout $workout): static
    {
        if ($this->workouts->removeElement($workout)) {
            if ($workout->getPerson() === $this) {
                $workout->setPerson(null);  // Reset the owning side
            }
        }

        return $this;
    }

    // Getter and setter for the user's profile image (optional relationship with Image entity)
    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): static
    {
        $this->image = $image;

        return $this;
    }
}