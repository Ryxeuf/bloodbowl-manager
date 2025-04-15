<?php

namespace App\Controller\Game;

use App\Entity\Game;
use App\Service\GameLogService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/game/{game}/logs', name: 'app_game_logs', methods: ['GET'])]
class GameLogController extends AbstractController
{
    public function __construct(
        private GameLogService $gameLogService
    ) {
    }

    public function __invoke(Game $game): Response
    {
        $logs = $this->gameLogService->getLatestLogs($game);
        
        return $this->render('game/_game_logs.html.twig', [
            'logs' => $logs,
            'game' => $game
        ]);
    }
} 