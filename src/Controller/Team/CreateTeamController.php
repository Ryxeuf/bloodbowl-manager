<?php

namespace App\Controller\Team;

use App\Entity\Faction;
use App\Service\TeamServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/teams/new', name: 'app_team_new', methods: ['GET', 'POST'])]
class CreateTeamController extends AbstractController
{
    public function __construct(
        private TeamServiceInterface $teamService
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($request->isMethod('POST')) {
            $faction = $this->teamService->getFactionById($request->request->get('faction'));
            if (!$faction) {
                $this->addFlash('error', 'Faction invalide');
                return $this->redirectToRoute('app_team_new');
            }

            $team = $this->teamService->createTeam(
                $request->request->get('name'),
                $faction,
                $user
            );

            $this->addFlash('success', 'Équipe créée avec succès');
            return $this->redirectToRoute('app_teams');
        }

        $factions = $this->teamService->getAllFactions();

        return $this->render('team/new.html.twig', [
            'factions' => $factions,
        ]);
    }
} 