<?php

namespace App\Controller\Game;

use App\Entity\Game;
use App\Service\GameServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/game/{id}/play', name: 'app_game_play', methods: ['GET', 'POST'])]
#[IsGranted('GAME_ACCESS', subject: 'game')]
class PlayGameController extends AbstractController
{
    public function __construct(
        private GameServiceInterface $gameService
    ) {
    }

    public function __invoke(Game $game, Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $action = $request->request->get('action');
            $this->gameService->updateGameScore($game, $action);
        }

        return $this->render('game/play.html.twig', [
            'game' => $game,
        ]);
    }
} 