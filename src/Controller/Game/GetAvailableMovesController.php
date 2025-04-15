<?php

namespace App\Controller\Game;

use App\Entity\Player;
use App\Entity\Game;
use App\Repository\PlayerGameStateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\PlayerGameState;

#[Route('/game/{game}/player/{player}/available-moves', name: 'app_game_available_moves', methods: ['GET'])]
class GetAvailableMovesController extends AbstractController
{
    public function __construct(
        private PlayerGameStateRepository $playerGameStateRepository
    ) {
    }

    private function isAdjacentToOpponent(int $x, int $y, array $opponentPositions): bool
    {
        foreach ($opponentPositions as $pos) {
            $dx = abs($x - $pos['x']);
            $dy = abs($y - $pos['y']);
            if ($dx <= 1 && $dy <= 1 && !($dx === 0 && $dy === 0)) {
                return true;
            }
        }
        return false;
    }

    private function getNeighbors(int $x, int $y, array $occupied, array $traversable): array
    {
        $neighbors = [];
        $directions = [
            [-1, -1], [0, -1], [1, -1],
            [-1, 0],           [1, 0],
            [-1, 1],  [0, 1],  [1, 1]
        ];

        foreach ($directions as [$dx, $dy]) {
            $newX = $x + $dx;
            $newY = $y + $dy;

            // Vérifier les limites du terrain
            if ($newX >= 1 && $newX <= 26 && $newY >= 1 && $newY <= 15) {
                // Vérifier si la case est occupée
                $isBlocked = false;
                $isTraversable = false;
                foreach ($occupied as $pos) {
                    if ($pos['x'] === $newX && $pos['y'] === $newY) {
                        foreach ($traversable as $tPos) {
                            if ($tPos['x'] === $newX && $tPos['y'] === $newY) {
                                $isTraversable = true;
                                break;
                            }
                        }
                        if (!$isTraversable) {
                            $isBlocked = true;
                        }
                        break;
                    }
                }
                if (!$isBlocked) {
                    $neighbors[] = [
                        'x' => $newX, 
                        'y' => $newY,
                        'traversableOnly' => $isTraversable
                    ];
                }
            }
        }

        return $neighbors;
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

        // Vérifier si l'équipe du joueur est l'équipe active
        $activeTeam = ($game->getCurrentTurn() % 2 === 1) ? $game->getHomeTeam() : $game->getAwayTeam();
        if ($player->getTeam() !== $activeTeam) {
            return new JsonResponse([
                'availableMoves' => [],
                'error' => 'Ce joueur ne fait pas partie de l\'équipe active'
            ]);
        }

        // Vérifier si le joueur est disponible
        if ($playerState->getState() !== PlayerGameState::STATE_AVAILABLE) {
            return new JsonResponse([
                'availableMoves' => [],
                'error' => 'Ce joueur n\'est pas disponible pour agir'
            ]);
        }

        // Si le joueur a déjà joué, on ne peut plus bouger
        if ($playerState->hasPlayed()) {
            return new JsonResponse(['availableMoves' => []]);
        }

        // Si le joueur n'a pas d'action en cours liée au mouvement, on ne peut pas bouger
        $validActions = [
            PlayerGameState::ACTION_MOVE,
            PlayerGameState::ACTION_BLITZ,
            PlayerGameState::ACTION_PASS,
            PlayerGameState::ACTION_HANDOFF
        ];
        
        if (!in_array($playerState->getCurrentAction(), $validActions)) {
            return new JsonResponse([
                'availableMoves' => [],
                'error' => 'Player must select a valid action first'
            ]);
        }
        
        // Si l'action est complétée, on ne peut plus bouger
        if ($playerState->getActionStatus() === PlayerGameState::ACTION_STATUS_COMPLETED) {
            return new JsonResponse(['availableMoves' => []]);
        }

        $currentX = $playerState->getX();
        $currentY = $playerState->getY();
        
        // Utiliser le mouvement restant si l'action est en cours, sinon utiliser le mouvement total
        $maxMovement = ($playerState->getActionStatus() === PlayerGameState::ACTION_STATUS_IN_PROGRESS)
            ? $playerState->getRemainingMovement()
            : $player->getM();

        // Récupérer toutes les positions occupées
        $occupiedPositions = $this->playerGameStateRepository->findBy(['game' => $game]);
        $occupied = [];
        $traversable = [];
        $opponentPositions = [];

        // Déterminer l'équipe du joueur actuel
        $playerTeam = $player->getTeam();

        foreach ($occupiedPositions as $state) {
            if ($state->getX() && $state->getY()) {
                $occupied[] = [
                    'x' => $state->getX(), 
                    'y' => $state->getY()
                ];

                // Si c'est un joueur adverse debout, on l'ajoute aux positions adverses
                if ($state->getPlayer()->getTeam() !== $playerTeam && 
                    $state->getState() === 'available') {
                    $opponentPositions[] = [
                        'x' => $state->getX(),
                        'y' => $state->getY()
                    ];
                }

                if ($state->getState() === 'prone' || $state->getState() === 'stunned') {
                    $traversable[] = [
                        'x' => $state->getX(), 
                        'y' => $state->getY()
                    ];
                }
            }
        }

        // Initialiser les structures pour l'algorithme
        $visited = [];
        $queue = new \SplPriorityQueue();
        $distances = [];
        $availableMoves = [];

        // Position initiale
        $queue->insert(
            ['x' => $currentX, 'y' => $currentY, 'traversableOnly' => false],
            0
        );
        $distances["$currentX,$currentY"] = 0;

        // Tant qu'il y a des cases à explorer et qu'on n'a pas dépassé le mouvement maximum
        while (!$queue->isEmpty()) {
            $current = $queue->extract();
            $currentDistance = $distances[$current['x'].','.$current['y']];

            // Si on a déjà visité cette case ou si on a dépassé le mouvement maximum
            if (isset($visited[$current['x'].','.$current['y']]) || $currentDistance > $maxMovement) {
                continue;
            }

            // Marquer comme visitée
            $visited[$current['x'].','.$current['y']] = true;

            // Si ce n'est pas la position de départ et que ce n'est pas une case traversable uniquement
            if (($current['x'] !== $currentX || $current['y'] !== $currentY) && !$current['traversableOnly']) {
                $availableMoves[] = [
                    'x' => $current['x'], 
                    'y' => $current['y'],
                    'requiresDiceRoll' => $this->isAdjacentToOpponent($current['x'], $current['y'], $opponentPositions),
                    'distance' => $currentDistance // Ajouter la distance pour information
                ];
            }

            // Explorer les voisins
            foreach ($this->getNeighbors($current['x'], $current['y'], $occupied, $traversable) as $neighbor) {
                $newDistance = $currentDistance + 1;
                $key = $neighbor['x'].','.$neighbor['y'];

                if (!isset($distances[$key]) || $newDistance < $distances[$key]) {
                    $distances[$key] = $newDistance;
                    $queue->insert($neighbor, -$newDistance);
                }
            }
        }

        return new JsonResponse([
            'availableMoves' => $availableMoves,
            'remainingMovement' => $maxMovement
        ]);
    }
} 