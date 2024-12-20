<?php

namespace App\Entity;

use App\Repository\WorkoutRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorkoutRepository::class)]
class Workout
{
    // Primary key, auto-generated by Doctrine
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Name of the workout (e.g., "Leg Day"), max length of 255 characters
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    // Date and time when the workout was created, using immutable DateTime
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    // Many-to-one relationship with the User entity (workout belongs to a user)
    #[ORM\ManyToOne(inversedBy: 'workouts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $person = null;

    /**
     * @var Collection<int, ExerciseLog>
     */
    // One-to-many relationship with ExerciseLog entities, mapped by the "workout" property in ExerciseLog
    // Automatically persist or remove ExerciseLogs when a Workout is persisted or removed (cascade and orphan removal)
    #[ORM\OneToMany(targetEntity: ExerciseLog::class, mappedBy: 'workout', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $exerciseLogs;

    // Constructor initializes the exerciseLogs collection and sets the createdAt field to the current time
    public function __construct()
    {
        $this->exerciseLogs = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    // Getter for the ID
    public function getId(): ?int
    {
        return $this->id;
    }

    // Getter and setter for the workout name
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    // Getter and setter for the workout creation date
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    // Getter and setter for the user associated with the workout
    public function getPerson(): ?User
    {
        return $this->person;
    }

    public function setPerson(?User $person): static
    {
        $this->person = $person;

        return $this;
    }

    /**
     * @return Collection<int, ExerciseLog>
     */
    // Getter for the collection of exercise logs associated with this workout
    public function getExerciseLogs(): Collection
    {
        return $this->exerciseLogs;
    }

    // Adds an exercise log to the workout and sets the workout reference in the log
    public function addExerciseLog(ExerciseLog $exerciseLog): static
    {
        if (!$this->exerciseLogs->contains($exerciseLog)) {
            $this->exerciseLogs->add($exerciseLog);
            $exerciseLog->setWorkout($this);
        }

        return $this;
    }

    // Removes an exercise log from the workout and resets the reference in the log
    public function removeExerciseLog(ExerciseLog $exerciseLog): static
    {
        if ($this->exerciseLogs->removeElement($exerciseLog) && $exerciseLog->getWorkout() === $this) {
            $exerciseLog->setWorkout(null);  // Reset the owning side
        }

        return $this;
    }
}
