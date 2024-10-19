<?php

namespace App\Entity;

use App\Repository\MuscleGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MuscleGroupRepository::class)]
class MuscleGroup
{
    // Primary key for the MuscleGroup entity, automatically generated
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Name of the muscle group (e.g., "Chest", "Legs"), limited to 100 characters
    #[ORM\Column(length: 100)]
    private ?string $name = null;

    // Body part associated with the muscle group (e.g., "Upper Body"), limited to 100 characters
    #[ORM\Column(length: 100)]
    private ?string $bodyPart = null;

    /**
     * @var Collection<int, Exercise>
     */
    // One-to-many relationship with Exercise entities
    // A muscle group can have multiple exercises associated with it
    #[ORM\OneToMany(targetEntity: Exercise::class, mappedBy: 'muscleGroup')]
    private Collection $exercises;

    // Constructor to initialize the $exercises as an ArrayCollection
    public function __construct()
    {
        $this->exercises = new ArrayCollection();
    }

    // Getter for $id, returns the unique identifier for the muscle group
    public function getId(): ?int
    {
        return $this->id;
    }

    // Getter for $name, returns the name of the muscle group
    public function getName(): ?string
    {
        return $this->name;
    }

    // Setter for $name, allows setting the name of the muscle group
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    // Getter for $bodyPart, returns the body part this muscle group belongs to
    public function getBodyPart(): ?string
    {
        return $this->bodyPart;
    }

    // Setter for $bodyPart, allows setting the body part associated with the muscle group
    public function setBodyPart(string $bodyPart): static
    {
        $this->bodyPart = $bodyPart;

        return $this;
    }

    /**
     * @return Collection<int, Exercise>
     */
    // Getter for $exercises, returns the collection of exercises associated with this muscle group
    public function getExercises(): Collection
    {
        return $this->exercises;
    }

    // Adds an exercise to the muscle group's collection of exercises
    public function addExercise(Exercise $exercise): static
    {
        if (!$this->exercises->contains($exercise)) {
            $this->exercises->add($exercise);
            $exercise->setMuscleGroup($this);  // Sets the owning side of the relationship
        }

        return $this;
    }

    // Removes an exercise from the muscle group's collection
    public function removeExercise(Exercise $exercise): static
    {
        if ($this->exercises->removeElement($exercise)) {
            // Ensures that the owning side is properly set to null if it's no longer associated
            if ($exercise->getMuscleGroup() === $this) {
                $exercise->setMuscleGroup(null);
            }
        }

        return $this;
    }
}
