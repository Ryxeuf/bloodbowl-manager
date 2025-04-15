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

#[Route('/game/{game}/player/{player}/action', name: 'app_game_player_action', methods: ['POST'])]
class PlayerActionController extends AbstractController
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

        // Si le joueur a déjà joué, on ne peut plus agir
        if ($playerState->hasPlayed()) {
            return new JsonResponse([
                'error' => 'Player has already played his turn'
            ], Response::HTTP_BAD_REQUEST);
        }

        // Récupérer les données de la requête
        $data = json_decode($request->getContent(), true);
        $action = $data['action'] ?? null;
        $status = $data['status'] ?? null;

        // Si on commence une action
        if ($status === 'start') {
            // Vérifier que l'action est valide
            $validActions = [
                PlayerGameState::ACTION_MOVE,
                PlayerGameState::ACTION_BLOCK,
                PlayerGameState::ACTION_BLITZ,
                PlayerGameState::ACTION_PASS,
                PlayerGameState::ACTION_HANDOFF,
                PlayerGameState::ACTION_FOUL
            ];

            if (!in_array($action, $validActions)) {
                return new JsonResponse([
                    'error' => 'Invalid action'
                ], Response::HTTP_BAD_REQUEST);
            }

            // Si une action est déjà en cours, on ne peut pas en commencer une nouvelle
            if ($playerState->getActionStatus() === PlayerGameState::ACTION_STATUS_IN_PROGRESS) {
                return new JsonResponse([
                    'error' => 'Player already has an action in progress'
                ], Response::HTTP_BAD_REQUEST);
            }

            // Commencer l'action
            $playerState->startAction($action);
            $this->entityManager->flush();

            return new JsonResponse([
                'success' => true,
                'action' => $action,
                'status' => PlayerGameState::ACTION_STATUS_IN_PROGRESS,
                'remainingMovement' => $playerState->getRemainingMovement()
            ]);
        }
        // Si on termine une action
        else if ($status === 'complete') {
            // Vérifier que l'action est en cours
            if ($playerState->getActionStatus() !== PlayerGameState::ACTION_STATUS_IN_PROGRESS) {
                return new JsonResponse([
                    'error' => 'No action in progress'
                ], Response::HTTP_BAD_REQUEST);
            }

            // Terminer l'action
            $playerState->completeAction();
            $playerState->setHasPlayed(true);
            $this->entityManager->flush();

            return new JsonResponse([
                'success' => true,
                'action' => $playerState->getCurrentAction(),
                'status' => PlayerGameState::ACTION_STATUS_COMPLETED
            ]);
        }
        // Si on annule une action
        else if ($status === 'cancel') {
            // Vérifier que l'action est en cours
            if ($playerState->getActionStatus() !== PlayerGameState::ACTION_STATUS_IN_PROGRESS) {
                return new JsonResponse([
                    'error' => 'No action in progress'
                ], Response::HTTP_BAD_REQUEST);
            }

            // Annuler l'action
            $playerState->resetAction();
            $this->entityManager->flush();

            return new JsonResponse([
                'success' => true,
                'action' => PlayerGameState::ACTION_NONE,
                'status' => PlayerGameState::ACTION_STATUS_NOT_STARTED
            ]);
        }
        else {
            return new JsonResponse([
                'error' => 'Invalid status'
            ], Response::HTTP_BAD_REQUEST);
        }
    }
} 