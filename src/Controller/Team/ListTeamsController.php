<?php

namespace App\Controller\Team;

use App\Service\TeamServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/teams', name: 'app_teams', methods: ['GET'])]
class ListTeamsController extends AbstractController
{
    public function __construct(
        private TeamServiceInterface $teamService
    ) {
    }

    public function __invoke(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $teams = $this->teamService->getTeamsForUser($user);

        return $this->render('team/index.html.twig', [
            'teams' => $teams,
        ]);
    }
} 