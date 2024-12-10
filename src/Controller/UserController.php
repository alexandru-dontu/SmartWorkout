<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\AuthService;
use App\Service\UserManagerService;
use App\Service\WorkoutService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    private UserManagerService $userManager;
    private AuthService $authService;

    public function __construct(UserManagerService $userManager, AuthService $authService)
    {
        $this->userManager = $userManager;
        $this->authService = $authService;
    }

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
    public function store(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $password = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);

            $this->userManager->saveUser($user);

            return $this->redirectToRoute('app_user');
        }

        return $this->render('user/register.html.twig', [
            'form' => $form,
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

    #[Route('/users/{id}/profile', name: 'user_profile')]
    public function showUserProfile(User $user): Response
    {
        $currentUser = $this->getUser();

        // Check if the logged-in user matches the profile being accessed
        if ($currentUser->getId() !== $user->getId()) {
            throw $this->createAccessDeniedException('Access denied: You can only view your own profile.');
        }

        $weight = $user->getLatestWeight();

        $weights = $user->getWeights();

        $weightData = [];
        foreach ($weights as $weight) {
            $weightData[] = [
                'dateRecorded' => $weight->getDateRecorded()->format('Y-m-d'),
                'weight' => $weight->getWeight(),
            ];
        }

        return $this->render('user/profile.html.twig', [
            'user' => $user, // Pass the user data to the view
            'weight' => $weights ? $weights->last()->getWeight() : null,
            'weightData' => $weightData,
        ]);
    }

    #[Route('/users/{id}/profile/upload', name: 'upload_profile_image')]
    public function uploadProfileImage(Request $request): Response
    {
        $user = $this->authService->getUser();
        $uploadedFile = $request->files->get('profileImage');

        if ($uploadedFile) {
            $uploadDir = $this->getParameter('images_directory');
            $success = $this->userManager->uploadProfileImage($user, $uploadedFile, $uploadDir);

            if ($success) {
                $this->addFlash('success', 'Profile picture updated successfully!');
            } else {
                $this->addFlash('error', 'Failed to upload image.');
            }
        } else {
            $this->addFlash('error', 'No file was uploaded.');
        }

        return $this->redirectToRoute('user_profile', ['id' => $user->getId()]);
    }
}
