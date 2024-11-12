<?php

namespace App\Service;

use App\Entity\Exercise;
use App\Entity\Image;
use App\Repository\ExerciseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class ExerciseService
{
    // Declare properties for the class
    private ExerciseRepository $exerciseRepository; // Repository for Exercise entity
    private EntityManagerInterface $entityManager;  // Entity manager for handling database operations
    private SluggerInterface $slugger;              // Service for creating URL-friendly slugs
    private string $imagesDirectory;                // Directory path for storing images

    // Constructor to initialize dependencies
    public function __construct(EntityManagerInterface $entityManager, ExerciseRepository $exerciseRepository, SluggerInterface $slugger, string $imagesDirectory)
    {
        $this->entityManager = $entityManager;          // Set the entity manager
        $this->exerciseRepository = $exerciseRepository; // Set the exercise repository
        $this->slugger = $slugger;                      // Set the slugger service
        $this->imagesDirectory = $imagesDirectory;      // Set the images directory path
    }

    // Fetch an exercise by its ID
    public function getExerciseById(int $exerciseId): object
    {
        return $this->exerciseRepository->find($exerciseId); // Use the repository to find and return the exercise
    }

    // Fetch exercises based on a specific muscle group
    public function getExercisesByMuscleGroup($muscleGroup): array
    {
        return $this->exerciseRepository->findByMuscleGroup($muscleGroup); // Return exercises from the repository
    }

    // Add a new exercise with an optional image upload
    public function addExercise(Exercise $exercise, ?UploadedFile $imageFile): array
    {
        try {
            // Check if an exercise with the same name already exists
            $existingExercise = $this->exerciseRepository->findByName($exercise->getName());
            if ($existingExercise) {
                throw new \UpdateException('An exercise with this name already exists!'); // Throw an error if it exists
            }
            // Handle image upload if an image file is provided
            if ($imageFile) {
                $this->handleImageUpload($exercise, $imageFile);
            }
            // Save the new exercise using the repository
            $this->exerciseRepository->create($exercise);
            return ['success' => true, 'message' => 'Exercise created successfully!']; // Return success message
        } catch (\Exception $exception) {
            return ['success' => false, 'message' => $exception->getMessage()]; // Return error message
        }
    }

    // Update an existing exercise with an optional image upload
    public function updateExercise(Exercise $exercise, ?UploadedFile $imageFile): array
    {
        try {
            // Check if another exercise with the same name exists, excluding the current one
            $existingExercise = $this->exerciseRepository->findByNameExcludingId($exercise->getName(), $exercise->getId());
            if ($existingExercise) {
                throw new \UpdateException('An exercise with this name already exists!'); // Throw an error if it exists
            }
            // Handle image upload if an image file is provided
            if ($imageFile) {
                $this->handleImageUpload($exercise, $imageFile);
            }
            // Update the exercise using the repository
            $this->exerciseRepository->update($exercise);
            return ['success' => true, 'message' => 'Exercise updated successfully!']; // Return success message
        } catch (\Exception $exception) {
            return ['success' => false, 'message' => $exception->getMessage()]; // Return error message
        }
    }

    // Handle the image upload for an exercise
    private function handleImageUpload(Exercise $exercise, UploadedFile $imageFile): void
    {
        // Get the original filename without the extension
        $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
        // Create a safe slug for the filename
        $safeFilename = $this->slugger->slug($originalFilename);
        // Create a new filename with a unique ID and the original file's extension
        $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

        try {
            // Move the uploaded file to the specified images directory
            $imageFile->move(
                $this->imagesDirectory,
                $newFilename
            );
        } catch (FileException $e) {
            // Throw an error if the file cannot be moved
            throw new \FileErrorException('Could not move the file: '.$e->getMessage());
        }

        // Create a new Image entity and associate it with the exercise
        $image = new Image();
        $image->setPath($newFilename); // Set the path of the uploaded image
        $exercise->setImage($image);    // Associate the image with the exercise
    }

    // Delete an exercise by its ID
    public function deleteExercise(int $id)
    {
        $this->exerciseRepository->delete($id); // Use the repository to delete the exercise
    }

    // Fetch exercises that belong to a specific workout
    public function getExercisesByWorkout($id)
    {
        return $this->exerciseRepository->findByWorkout($id); // Return exercises from the repository
    }
}
