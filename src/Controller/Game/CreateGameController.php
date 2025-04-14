<?php

namespace App\Controller\Game;

use App\Entity\Team;
use App\Service\GameServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/game/new', name: 'app_game_new', methods: ['GET', 'POST'])]
class CreateGameController extends AbstractController
{
    public function __construct(
        private GameServiceInterface $gameService
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $user = $this->getUser();
        $teams = $this->gameService->getTeamsForUser($user);

        if ($request->isMethod('POST')) {
            $homeTeamId = $request->request->get('homeTeam');
            $awayTeamId = $request->request->get('awayTeam');

            $homeTeam = $this->gameService->getTeamById($homeTeamId);
            $awayTeam = $this->gameService->getTeamById($awayTeamId);

            if (!$homeTeam || !$awayTeam) {
                $this->addFlash('error', 'Équipes non trouvées');
                return $this->redirectToRoute('app_game_new');
            }

            $game = $this->gameService->createGame($homeTeam, $awayTeam);

            return $this->redirectToRoute('app_game_play', ['id' => $game->getId()]);
        }

        return $this->render('game/new.html.twig', [
            'teams' => $teams,
        ]);
    }
} 