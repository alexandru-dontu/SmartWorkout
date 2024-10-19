<?php

namespace App\Controller;

use App\Entity\Exercise;
use App\Form\ExerciseType;
use App\Repository\ExerciseLogRepository;
use App\Repository\ExerciseRepository;
use App\Repository\MuscleGroupRepository;
use App\Repository\WorkoutRepository;
use App\Service\ExerciseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ExerciseController extends AbstractController
{
    // This private property will store the security service instance.
    private Security $security;

    // Constructor injection of the Security service to manage user authentication and access control.
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    // Route to handle exercise creation
    #[Route('/exercise/create', name: 'app_exercise')]
    public function create(Request $request, ExerciseService $exerciseService): Response
    {
        // Create a new Exercise entity
        $exercise = new Exercise();

        // Create a form based on the ExerciseType form definition
        $form = $this->createForm(ExerciseType::class, $exercise);

        // Handle the form request (processes the form submission)
        $form->handleRequest($request);

        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Get the submitted form data and file
            $exercise = $form->getData();
            $imageFile = $form->get('image_file')->getData();

            // Add the exercise using the ExerciseService
            $result = $exerciseService->addExercise($exercise, $imageFile);

            // Check if the result was successful, otherwise redirect with error
            if (!$result['success']) {
                $this->addFlash('error', $result['message']);
                return $this->redirectToRoute('app_exercise');
            }

            // If successful, add success message and redirect to exercise listing page
            $this->addFlash('success', $result['message']);
            return $this->redirectToRoute('show_exercises');
        }

        // Render the exercise creation template and pass the form
        return $this->render('exercise/create.html.twig', [
            'form' => $form,
            'action' => 'create',  // Action for the form (create mode)
        ]);
    }

    // Route to show all exercises
    #[Route('/exercise', name: 'show_exercises')]
    public function show(ExerciseRepository $exerciseRepository): Response
    {
        // Fetch all exercises from the repository
        $exercises = $exerciseRepository->findAll();

        // Render the exercises in a template
        return $this->render('exercise/show.html.twig', [
            'exercises' => $exercises,
            'type' =>'exercise',  // Page type (exercise view mode)
        ]);
    }

    // Route to show exercises by a specific muscle group
    #[Route('/muscle/group/{id}/exercises', name: 'muscle_group_exercises')]
    public function showMuscleGroupExercises(ExerciseService $exerciseService, MuscleGroupRepository $muscleGroupRepository, $id): Response
    {
        // Find the muscle group by its ID
        $muscleGroup = $muscleGroupRepository->find($id);

        // Get the logged-in user
        $user = $this->security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('You are not logged in.');
        }

        // Check if the muscle group exists
        if (!$muscleGroup) {
            throw $this->createNotFoundException('Muscle group not found');
        }

        // Get exercises related to the muscle group via the service
        $exercises = $exerciseService->getExercisesByMuscleGroup($id);

        // Render the muscle group exercises in the template
        return $this->render('exercise/show.html.twig', [
            'muscleGroup' => $muscleGroup,
            'exercises' => $exercises,
            'type' => 'muscle',  // Page type (muscle group exercises)
        ]);
    }

    // Route to show exercises for a specific workout
    #[Route('/workout/{id}/exercises', name: 'workout_exercises')]
    public function showWorkoutExercises(ExerciseService $exerciseService, WorkoutRepository $workoutRepository, $id): Response
    {
        // Find the workout by its ID
        $workout = $workoutRepository->find($id);

        // Check if the workout exists
        if (!$workout) {
            throw $this->createNotFoundException('Workout not found');
        }

        // Get exercises related to the workout via the service
        $exercises = $exerciseService->getExercisesByWorkout($id);

        // Render the workout exercises in the template
        return $this->render('exercise/showLog.html.twig', [
            'workout' => $workout,
            'exercises' => $exercises,
        ]);
    }

    // Route to update an exercise
    #[Route('/exercise/{id}', name: 'edit_exercise', methods: ['GET', 'POST'])]
    public function update(Request $request, ExerciseService $exerciseService, int $id): Response
    {
        // Fetch the exercise by its ID via the service
        $exercise = $exerciseService->getExerciseById($id);
        if (!$exercise) {
            throw $this->createNotFoundException('No exercise found for id ' . $id);
        }

        // Ensure the user is logged in
        $user = $this->security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('You are not logged in.');
        }

        // Create the form and handle the request
        $form = $this->createForm(ExerciseType::class, $exercise);
        $form->handleRequest($request);

        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Get the submitted data and update the exercise
            $exercise = $form->getData();
            $imageFile = $form->get('image_file')->getData();
            $result = $exerciseService->updateExercise($exercise, $imageFile);

            // Check if the update was successful
            if (!$result['success']) {
                $this->addFlash('error', $result['message']);
                return $this->redirectToRoute('edit_exercise', ['id' => $id]);
            }

            // Add success message and redirect to the exercise list
            $this->addFlash('success', $result['message']);
            return $this->redirectToRoute('show_exercises');
        }

        // Render the update form in the template
        return $this->render('exercise/create.html.twig', [
            'form' => $form,
            'action' => 'update',  // Action for the form (update mode)
        ]);
    }

    // Route to delete an exercise
    #[Route('/exercise/delete/{id}', name: 'delete_exercise', methods: ['DELETE'])]
    public function destroy(Request $request, ExerciseService $exerciseService, int $id)
    {
        // Delete the exercise via the service
        $exerciseService->deleteExercise($exerciseService->getExerciseById($id)->getId());

        // Redirect back to the exercise list
        return $this->redirectToRoute('show_exercises');
    }

    // Route to show logs related to a specific exercise
    #[Route('/exercise/{id}/logs', name: 'exercise_logs')]
    public function showExerciseLogs(ExerciseLogRepository $exerciseLogRepository, ExerciseService $exerciseService, int $id): Response
    {
        // Get the current user and check if they are logged in
        $user = $this->security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('You are not logged in.');
        }

        // Get exercise logs for the specific exercise and user
        $logs = $exerciseLogRepository->findLogsByExerciseAndUser($id, $user->getId());

        // Get the name of the exercise
        $exerciseName = $exerciseService->getExerciseById($id)->getName();

        // Render the logs template
        return $this->render('exercise/logs.html.twig', [
            'logs' => $logs,
            'exerciseName' => $exerciseName,
        ]);
    }
}

