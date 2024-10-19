<?php

namespace App\Entity;

use App\Repository\ExerciseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExerciseRepository::class)]
class Exercise
{
    // Primary key: Unique identifier for the Exercise entity
    #[ORM\Id]
    #[ORM\GeneratedValue] // Auto-incremented value
    #[ORM\Column]
    private ?int $id = null;

    // Name of the exercise
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    // Many-to-one relationship with the Image entity, allowing an exercise to have one associated image
    #[ORM\ManyToOne(cascade: ['persist', 'remove'])] // Cascade ensures that image is persisted or removed with exercise
    private ?Image $image = null;

    // Many-to-one relationship with MuscleGroup; each exercise is linked to one muscle group
    #[ORM\ManyToOne(inversedBy: 'exercises')] // This refers to the inverse side in MuscleGroup, which has a collection of exercises
    #[ORM\JoinColumn(nullable: false)] // Ensures this column cannot be null
    private ?MuscleGroup $muscleGroup = null;

    // Boolean indicating whether the exercise is bodyweight-based
    #[ORM\Column]
    private ?bool $isBodyWeight = null;

    // Getter for $id, returns the unique identifier of the exercise
    public function getId(): ?int
    {
        return $this->id;
    }

    // Getter for $name, returns the name of the exercise
    public function getName(): ?string
    {
        return $this->name;
    }

    // Setter for $name, allows setting a new name for the exercise
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    // Getter for $muscleGroup, returns the associated muscle group of the exercise
    public function getMuscleGroup(): ?MuscleGroup
    {
        return $this->muscleGroup;
    }

    // Setter for $muscleGroup, allows setting a new muscle group for the exercise
    public function setMuscleGroup(?MuscleGroup $muscleGroup): static
    {
        $this->muscleGroup = $muscleGroup;

        return $this;
    }

    // Getter for $image, returns the associated image of the exercise
    public function getImage(): ?Image
    {
        return $this->image;
    }

    // Setter for $image, allows setting a new image for the exercise
    public function setImage(?Image $image): self
    {
        $this->image = $image;

        return $this;
    }

    // Getter for $isBodyWeight, returns true if the exercise is bodyweight-based
    public function getIsBodyWeight(): bool
    {
        return $this->isBodyWeight;
    }

    // Setter for $isBodyWeight, allows setting whether the exercise is bodyweight-based
    public function setIsBodyWeight(bool $isBodyWeight): static
    {
        $this->isBodyWeight = $isBodyWeight;

        return $this;
    }
}
