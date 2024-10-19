<?php

namespace App\Entity;

use App\Repository\ExerciseLogRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExerciseLogRepository::class)]
class ExerciseLog
{
    // Primary key: Unique identifier for the ExerciseLog entity
    #[ORM\Id]
    #[ORM\GeneratedValue] // Auto-incremented value
    #[ORM\Column]
    private ?int $id = null;

    // Duration of the exercise in minutes or seconds (can be adjusted as needed)
    #[ORM\Column]
    private ?int $duration = null;

    // Number of repetitions for the exercise
    #[ORM\Column]
    private ?int $reps = null;

    // Number of sets performed
    #[ORM\Column]
    private ?int $sets = null;

    // Many-to-one relationship with Workout entity, each log belongs to one workout
    #[ORM\ManyToOne(inversedBy: 'exerciseLogs')] // Workout has a collection of ExerciseLogs
    #[ORM\JoinColumn(nullable: false)] // Ensures this column cannot be null
    private ?Workout $workout = null;

    // Many-to-one relationship with Exercise entity, each log is for one specific exercise
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)] // Ensures the exercise field is mandatory
    private ?Exercise $exercise = null;

    // Weight lifted during the exercise (optional, can be null if not relevant)
    #[ORM\Column(nullable: true)]
    private ?int $weight = null;

    // Getter for $id, returns the unique identifier of the log
    public function getId(): ?int
    {
        return $this->id;
    }

    // Getter for $duration, returns the duration of the exercise log
    public function getDuration(): ?int
    {
        return $this->duration;
    }

    // Setter for $duration, allows setting the duration of the exercise
    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    // Getter for $reps, returns the number of repetitions
    public function getReps(): ?int
    {
        return $this->reps;
    }

    // Setter for $reps, allows setting the number of repetitions
    public function setReps(int $reps): static
    {
        $this->reps = $reps;

        return $this;
    }

    // Getter for $sets, returns the number of sets
    public function getSets(): ?int
    {
        return $this->sets;
    }

    // Setter for $sets, allows setting the number of sets
    public function setSets(int $sets): static
    {
        $this->sets = $sets;

        return $this;
    }

    // Getter for $workout, returns the associated workout
    public function getWorkout(): ?Workout
    {
        return $this->workout;
    }

    // Setter for $workout, allows setting a workout for the log
    public function setWorkout(?Workout $workout): static
    {
        $this->workout = $workout;

        return $this;
    }

    // Getter for $exercise, returns the associated exercise
    public function getExercise(): ?Exercise
    {
        return $this->exercise;
    }

    // Setter for $exercise, allows setting an exercise for the log
    public function setExercise(?Exercise $exercise): static
    {
        $this->exercise = $exercise;

        return $this;
    }

    // Getter for $weight, returns the weight lifted during the exercise (if applicable)
    public function getWeight(): ?int
    {
        return $this->weight;
    }

    // Setter for $weight, allows setting the weight lifted (can be null)
    public function setWeight(?int $weight): static
    {
        $this->weight = $weight;

        return $this;
    }
}
