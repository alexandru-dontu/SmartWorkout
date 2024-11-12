<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Workout;
use App\Repository\WorkoutRepository;

class WorkoutService
{
    // Declare the repository for workouts
    private WorkoutRepository $workoutRepository;

    // Constructor to initialize the WorkoutRepository
    public function __construct(WorkoutRepository $workoutRepository)
    {
        $this->workoutRepository = $workoutRepository; // Set the repository for workouts
    }

    // Method to store a new workout
    public function store(Workout $workout): array
    {
        try {
            // Check if a workout with the same name already exists
            $existingWorkout = $this->workoutRepository->findByName($workout->getName());

            if ($existingWorkout) {
                // Throw an exception if the workout already exists
                throw new \CreateErrorException('A workout with this name already exists!');
            }

            // Create the new workout using the repository
            $this->workoutRepository->create($workout);

            // Return a success message
            return ['success' => true, 'message' => 'Workout created successfully!'];
        } catch (\Exception $exception) {
            // Return an error message in case of exception
            return ['success' => false, 'message' => $exception->getMessage()];
        }
    }

    // Method to find workouts associated with a specific user
    public function findByUser(?User $user)
    {
        return $this->workoutRepository->findBy(['person' => $user]); // Return workouts related to the user
    }

    // Method to get a workout by its ID
    public function getWorkoutById(int $workoutId)
    {
        return $this->workoutRepository->find($workoutId); // Return the workout object by ID
    }

    // Method to delete a workout by its ID
    public function deleteWorkout(int $id): void
    {
        $this->workoutRepository->delete($id); // Call the repository to delete the workout
    }

    // Method to find all workouts
    public function findAllWorkouts(): array
    {
        return $this->workoutRepository->findAll(); // Return an array of all workouts
    }
}
