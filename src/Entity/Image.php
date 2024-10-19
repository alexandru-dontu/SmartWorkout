<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    // Primary key for the Image entity, automatically generated
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Path of the image, stored as a string (e.g., URL or file path)
    #[ORM\Column(length: 255)]
    private ?string $path = null;

    /**
     * @var Collection<int, User>
     */
    // One-to-many relationship with the User entity
    // An image can be associated with multiple users
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'image')]
    private Collection $users;

    // Constructor to initialize the $users as an ArrayCollection
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    // Getter for $id, returns the unique identifier for the image
    public function getId(): ?int
    {
        return $this->id;
    }

    // Getter for $path, returns the file path or URL of the image
    public function getPath(): ?string
    {
        return $this->path;
    }

    // Setter for $path, allows setting the path of the image
    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    // Getter for $users, returns the collection of users associated with this image
    public function getUsers(): Collection
    {
        return $this->users;
    }

    // Adds a user to the image's collection and sets this image as the user's image
    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setImage($this);
        }

        return $this;
    }

    // Removes a user from the image's collection and sets the user's image to null
    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // Ensure the user's image is null if it was set to this image
            if ($user->getImage() === $this) {
                $user->setImage(null);
            }
        }

        return $this;
    }
}
