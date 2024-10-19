<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\WorkoutService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    // Route to the user index page
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        // Renders a basic view for the user controller index
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    // Route to handle user registration
    #[Route('/user/register', name: 'register_user')]
    public function store(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        // Create a new User entity
        $user = new User();

        // Create a form based on the UserType form definition, linked to the User entity
        $form = $this->createForm(UserType::class, $user);

        // Handle the form request (processes the form submission)
        $form->handleRequest($request);

        // If form is submitted and valid, process the user registration
        if ($form->isSubmitted() && $form->isValid()) {
            // Get the data submitted through the form
            $user = $form->getData();

            // Hash the user's password before saving it to the database
            $password = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($password);

            // Set default role to 'ROLE_USER'
            $user->setRoles(['ROLE_USER']);

            // Save the user to the database using the repository
            $userRepository->saveUser($user);

            // Redirect to the user index page after successful registration
            return $this->redirectToRoute('app_user');
        }

        // Render the registration form
        return $this->render('user/register.html.twig', [
            'form' => $form, // Pass the form to the view
        ]);
    }

    // Route to display all users
    #[Route('/users', name: 'show_users')]
    public function show(UserRepository $userRepository): Response
    {
        // Retrieve all users from the database using the repository
        $users = $userRepository->findAll();

        // If no users are found, throw a not found exception
        if (!$users) {
            throw $this->createNotFoundException('No users found');
        }

        // Render the list of users
        return $this->render('user/show.html.twig', [
            'users' => $users, // Pass the list of users to the view
        ]);
    }

    // Route to display a specific user's details
    #[Route('/users/{id}', name: 'user_details')]
    public function showUserDetails(User $user, WorkoutService $workoutService): Response
    {
        // Find all workouts associated with the user using the WorkoutService
        $workouts = $workoutService->findByUser($user);

        // Render the user details and their associated workouts
        return $this->render('user/details.html.twig', [
            'user' => $user, // Pass the user data to the view
            'workouts' => $workouts, // Pass the user's workouts to the view
        ]);
    }
}
