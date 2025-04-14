<?php

namespace App\Controller\Team;

use App\Entity\Team;
use App\Service\TeamServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/teams/{id}', name: 'app_team_show', methods: ['GET'])]
class ShowTeamController extends AbstractController
{
    public function __construct(
        private TeamServiceInterface $teamService
    ) {
    }

    public function __invoke(Team $team): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if (!$this->teamService->canAccessTeam($team, $user)) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('team/show.html.twig', [
            'team' => $team,
        ]);
    }
} 