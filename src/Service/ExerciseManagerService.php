<?php

namespace App\Service;

use App\Repository\ExerciseLogRepository;
use App\Repository\ExerciseRepository;
use App\Repository\MuscleGroupRepository;
use App\Repository\WorkoutRepository;

class ExerciseManagerService
{
    private ExerciseRepository $exerciseRepository;
    private ExerciseLogRepository $exerciseLogRepository;
    private MuscleGroupRepository $muscleGroupRepository;
    private WorkoutRepository $workoutRepository;

    public function __construct(
        ExerciseRepository $exerciseRepository,
        ExerciseLogRepository $exerciseLogRepository,
        MuscleGroupRepository $muscleGroupRepository,
        WorkoutRepository $workoutRepository
    ) {
        $this->exerciseRepository = $exerciseRepository;
        $this->exerciseLogRepository = $exerciseLogRepository;
        $this->muscleGroupRepository = $muscleGroupRepository;
        $this->workoutRepository = $workoutRepository;
    }

    public function findAllExercises()
    {
        return $this->exerciseRepository->findAll();
    }

    public function findMuscleGroup($id)
    {
        return $this->muscleGroupRepository->find($id);
    }

    public function findWorkout($id)
    {
        return $this->workoutRepository->find($id);
    }

    public function findLogsByExerciseAndUser($exerciseId, $userId)
    {
        return $this->exerciseLogRepository->findLogsByExerciseAndUser($exerciseId, $userId);
    }
}
