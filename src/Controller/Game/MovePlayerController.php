<?php

namespace App\Controller\Game;

use App\Entity\Player;
use App\Entity\Game;
use App\Entity\PlayerGameState;
use App\Repository\PlayerGameStateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/game/{game}/player/{player}/move', name: 'app_game_move_player', methods: ['POST'])]
class MovePlayerController extends AbstractController
{
    public function __construct(
        private PlayerGameStateRepository $playerGameStateRepository,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(Request $request, Game $game, Player $player): Response
    {
        // Récupérer l'état actuel du joueur
        $playerState = $this->playerGameStateRepository->findOneBy([
            'game' => $game,
            'player' => $player
        ]);

        if (!$playerState) {
            return new JsonResponse(['error' => 'Player state not found'], Response::HTTP_NOT_FOUND);
        }

        // Vérifier si l'équipe du joueur est l'équipe active
        $activeTeam = ($game->getCurrentTurn() % 2 === 1) ? $game->getHomeTeam() : $game->getAwayTeam();
        if ($player->getTeam() !== $activeTeam) {
            return new JsonResponse([
                'error' => 'Ce joueur ne fait pas partie de l\'équipe active'
            ], Response::HTTP_FORBIDDEN);
        }

        // Vérifier si le joueur est disponible
        if ($playerState->getState() !== PlayerGameState::STATE_AVAILABLE) {
            return new JsonResponse([
                'error' => 'Ce joueur n\'est pas disponible pour agir'
            ], Response::HTTP_FORBIDDEN);
        }

        // Si le joueur a déjà joué, on ne peut plus bouger
        if ($playerState->hasPlayed()) {
            return new JsonResponse([
                'error' => 'Player has already played his turn'
            ], Response::HTTP_BAD_REQUEST);
        }

        // Vérifier que le joueur est en train de faire une action de mouvement
        $validActions = [
            PlayerGameState::ACTION_MOVE,
            PlayerGameState::ACTION_BLITZ,
            PlayerGameState::ACTION_PASS,
            PlayerGameState::ACTION_HANDOFF
        ];

        if (!in_array($playerState->getCurrentAction(), $validActions) || 
            $playerState->getActionStatus() !== PlayerGameState::ACTION_STATUS_IN_PROGRESS) {
            return new JsonResponse([
                'error' => 'Player must start a valid movement action first'
            ], Response::HTTP_BAD_REQUEST);
        }

        // Récupérer les coordonnées cibles
        $data = json_decode($request->getContent(), true);
        $targetX = $data['x'] ?? null;
        $targetY = $data['y'] ?? null;

        if ($targetX === null || $targetY === null) {
            return new JsonResponse([
                'error' => 'Target coordinates are required'
            ], Response::HTTP_BAD_REQUEST);
        }

        // Vérifier que la case cible est libre
        $occupiedPositions = $this->playerGameStateRepository->findBy(['game' => $game]);
        foreach ($occupiedPositions as $state) {
            if ($state->getX() === $targetX && $state->getY() === $targetY) {
                return new JsonResponse([
                    'error' => 'Target position is already occupied'
                ], Response::HTTP_BAD_REQUEST);
            }
        }

        // Calculer la distance parcourue
        $currentX = $playerState->getX();
        $currentY = $playerState->getY();
        $dx = abs($targetX - $currentX);
        $dy = abs($targetY - $currentY);
        $distance = max($dx, $dy); // Diagonale = 1 case

        // Vérifier que le joueur a assez de mouvement restant
        if ($distance > $playerState->getRemainingMovement()) {
            return new JsonResponse([
                'error' => 'Not enough remaining movement'
            ], Response::HTTP_BAD_REQUEST);
        }

        // Mettre à jour la position du joueur
        $playerState->setX($targetX);
        $playerState->setY($targetY);
        
        // Réduire le mouvement restant
        $playerState->moveBy($distance);
        
        $this->entityManager->flush();

        return new JsonResponse([
            'success' => true,
            'position' => [
                'x' => $targetX,
                'y' => $targetY
            ],
            'remainingMovement' => $playerState->getRemainingMovement()
        ]);
    }
} 