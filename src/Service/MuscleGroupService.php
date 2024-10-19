<?php

namespace App\Service;

use App\Entity\MuscleGroup;
use App\Repository\MuscleGroupRepository;

class MuscleGroupService
{
    // Declare the repository for muscle groups
    private MuscleGroupRepository $muscleGroupRepository;

    // Constructor to initialize the MuscleGroupRepository
    public function __construct(MuscleGroupRepository $muscleGroupRepository)
    {
        $this->muscleGroupRepository = $muscleGroupRepository; // Set the repository for muscle groups
    }

    // Method to add a new muscle group
    public function addMuscleGroup(MuscleGroup $muscleGroup): array
    {
        try {
            // Check if a muscle group with the same name already exists
            $existingMuscleGroup = $this->muscleGroupRepository->findByName($muscleGroup->getName());
            if ($existingMuscleGroup) {
                // Throw an exception if the muscle group already exists
                throw new \Exception('A muscle group with this name already exists!');
            }

            // Create the new muscle group using the repository
            $this->muscleGroupRepository->create($muscleGroup);

            // Return a success message
            return ['success' => true, 'message' => 'Muscle group created successfully!'];
        } catch (\Exception $exception) {
            // Return an error message in case of exception
            return ['success' => false, 'message' => $exception->getMessage()];
        }
    }
}
