<?php

namespace App\Controller\Game;

use App\Entity\Player;
use App\Entity\Game;
use App\Repository\PlayerGameStateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/game/{game}/player/{player}/state', name: 'app_game_player_state', methods: ['GET'])]
class GetPlayerStateController extends AbstractController
{
    public function __construct(
        private PlayerGameStateRepository $playerGameStateRepository
    ) {
    }

    public function __invoke(Game $game, Player $player): Response
    {
        // Récupérer l'état actuel du joueur
        $playerState = $this->playerGameStateRepository->findOneBy([
            'game' => $game,
            'player' => $player
        ]);

        if (!$playerState) {
            return new JsonResponse(['error' => 'Player state not found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'id' => $playerState->getId(),
            'playerId' => $player->getId(),
            'gameId' => $game->getId(),
            'state' => $playerState->getState(),
            'x' => $playerState->getX(),
            'y' => $playerState->getY(),
            'hasPlayed' => $playerState->hasPlayed(),
            'currentAction' => $playerState->getCurrentAction(),
            'actionStatus' => $playerState->getActionStatus(),
            'remainingMovement' => $playerState->getRemainingMovement()
        ]);
    }
} 