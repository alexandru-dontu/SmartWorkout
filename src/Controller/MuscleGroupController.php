<?php

namespace App\Controller;

use App\Entity\MuscleGroup;
use App\Form\MuscleGroupType;
use App\Repository\MuscleGroupRepository;
use App\Service\MuscleGroupService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MuscleGroupController extends AbstractController
{
    // Route to handle the creation of a new muscle group
    #[Route('/muscle/group/create', name: 'app_muscle_group')]
    public function create(Request $request, MuscleGroupService $muscleGroupService): Response
    {
        // Create a new instance of MuscleGroup entity
        $muscleGroup = new MuscleGroup();

        // Create a form based on the MuscleGroupType form definition
        $form = $this->createForm(MuscleGroupType::class, $muscleGroup);

        // Handle the form request (processes the form submission)
        $form->handleRequest($request);

        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Get the submitted data from the form
            $muscleGroup = $form->getData();

            // Use the service to add the muscle group
            $result = $muscleGroupService->addMuscleGroup($muscleGroup);

            // If the operation fails, flash an error message and reload the form
            if (!$result['success']) {
                $this->addFlash('error', $result['message']);
                return $this->redirectToRoute('app_muscle_group');
            }

            // If successful, flash a success message and redirect to the muscle groups list
            $this->addFlash('success', $result['message']);
            return $this->redirectToRoute('show_muscle_groups');
        }

        // Render the form for creating a new muscle group
        return $this->render('muscle_group/create.html.twig', [
            'form' => $form,  // Pass the form to the view
        ]);
    }

    // Route to display all muscle groups
    #[Route('/muscle/group', name: 'show_muscle_groups')]
    public function show(MuscleGroupRepository $muscleGroupRepository): Response
    {
        // Retrieve all muscle groups from the repository
        $muscleGroups = $muscleGroupRepository->findAll();

        // Render the muscle groups in the view template
        return $this->render('muscle_group/show.html.twig', [
            'muscleGroups' => $muscleGroups  // Pass the list of muscle groups to the view
        ]);
    }
}
