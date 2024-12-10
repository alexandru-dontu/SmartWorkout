<?php

namespace App\Controller;

use App\Entity\UserWeights;
use App\Repository\UserWeightsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserWeightController extends AbstractController
{
    private $userWeightsRepository;

    public function __construct(UserWeightsRepository $userWeightsRepository)
    {
        $this->userWeightsRepository = $userWeightsRepository;
    }

    #[Route('/add-weight', name: 'add_user_weight')]
    public function addWeight(UserWeightsRepository $userWeightsRepository, Request $request)
    {
        $userWeight = new UserWeights();

        $userWeight->setUser($this->getUser());

        $weight = $request->request->get('weight');
        if ($weight === null) {
            $this->addFlash('error', 'Weight cannot be null.');
            return $this->redirectToRoute('user_profile', ['id' => $userWeight->getUser()->getId()]);
        }
        $userWeight->setWeight($weight);

        $userWeight->setDateRecorded(new \DateTimeImmutable());

        $userWeightsRepository->save($userWeight);

        return $this->redirectToRoute('user_profile', ['id' => $userWeight->getUser()->getId()]);
    }
}
