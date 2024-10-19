<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Workout;
use App\Form\WorkoutType;
use App\Service\WorkoutService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WorkoutController extends AbstractController
{
    // Route for creating a workout
    #[Route('workout/create', name: 'app_workout')]
    public function store(Request $request, WorkoutService $workoutService): Response
    {
        // Get the currently logged-in user
        $user = $this->getUser();

        // If no user is logged in, flash an error message and redirect to the workout listing page
        if (!$user) {
            $this->addFlash('error', 'You have to be logged in to create a workout!');
            return $this->redirectToRoute('show_workouts');
        }

        // Create a new instance of the Workout entity
        $workout = new Workout();

        // Create the form for the workout, using the WorkoutType form definition
        $form = $this->createForm(WorkoutType::class, $workout);

        // Handle the form submission
        $form->handleRequest($request);

        // If the form is submitted and valid, process the form data
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Workout $workout */
            $workout = $form->getData();

            /** @var User $user */
            // Get the current logged-in user and set it as the owner of the workout
            if ($user) {
                $workout->setPerson($user);
            }

            // Save the workout using the WorkoutService
            $workoutService->store($workout);

            // After saving, redirect to the workout listing page
            return $this->redirectToRoute('show_workouts');
        }

        // Render the form for creating a new workout
        return $this->render('workout/create.html.twig', [
            'form' => $form, // Pass the form to the view
        ]);
    }

    // Route for showing all workouts
    #[Route('/workout', name: 'show_workouts')]
    public function show(WorkoutService $workoutService): Response
    {
        // Get the currently logged-in user
        $user = $this->getUser();
        $workouts = null;

        // If the user is logged in
        if ($user) {
            // Check if the user has the 'ROLE_TRAINER' role, if so, show all workouts
            if (in_array('ROLE_TRAINER', $user->getRoles())) {
                $workouts = $workoutService->findAllWorkouts();
            }
            // Otherwise, show only the workouts associated with the current user
            else {
                $workouts = $workoutService->findByUser($user);
            }
        }

        // Render the view with the list of workouts
        return $this->render('workout/show.html.twig', [
            'workouts' => $workouts, // Pass the workouts to the view
        ]);
    }

    // Route for deleting a workout by its ID
    #[Route('/workout/delete/{id}', name: 'delete_workout', methods: ['DELETE'])]
    public function destroy(Request $request, WorkoutService $workoutService, int $id)
    {
        // Delete the workout using the WorkoutService by finding it by its ID
        $workoutService->deleteWorkout($workoutService->getWorkoutById($id)->getId());

        // Redirect to the workout listing page after deletion
        return $this->redirectToRoute('show_workouts');
    }

    // Route to redirect to the workout listing page (used as the homepage)
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        // Redirect to the workout listing page
        return $this->redirectToRoute('show_workouts');
    }
}
