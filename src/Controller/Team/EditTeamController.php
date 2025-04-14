<?php

namespace App\Controller\Team;

use App\Entity\Team;
use App\Service\TeamServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/teams/{id}/edit', name: 'app_team_edit', methods: ['GET', 'POST'])]
#[IsGranted('TEAM_ACCESS', subject: 'team')]
class EditTeamController extends AbstractController
{
    public function __construct(
        private TeamServiceInterface $teamService
    ) {
    }

    public function __invoke(Team $team, Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $name = $request->request->get('name');
            $this->teamService->updateTeam($team, $name);

            $this->addFlash('success', 'Équipe mise à jour avec succès');
            return $this->redirectToRoute('app_team_show', ['id' => $team->getId()]);
        }

        return $this->render('team/edit.html.twig', [
            'team' => $team,
        ]);
    }
} 