<?php

namespace App\Controller\Game;

use App\Entity\Player;
use App\Entity\Game;
use App\Repository\PlayerGameStateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/game/{game}/player/{player}/available-moves', name: 'app_game_available_moves', methods: ['GET'])]
class GetAvailableMovesController extends AbstractController
{
    public function __construct(
        private PlayerGameStateRepository $playerGameStateRepository
    ) {
    }

    private function getNeighbors(int $x, int $y, array $occupied): array
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
                $isOccupied = false;
                foreach ($occupied as $pos) {
                    if ($pos['x'] === $newX && $pos['y'] === $newY) {
                        $isOccupied = true;
                        break;
                    }
                }
                if (!$isOccupied) {
                    $neighbors[] = ['x' => $newX, 'y' => $newY];
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

        // Si le joueur a déjà joué, on ne peut plus bouger
        if ($playerState->hasPlayed()) {
            return new JsonResponse(['availableMoves' => []]);
        }

        $currentX = $playerState->getX();
        $currentY = $playerState->getY();
        $maxMovement = $player->getM();

        // Récupérer toutes les positions occupées
        $occupiedPositions = $this->playerGameStateRepository->findBy(['game' => $game]);
        $occupied = [];
        foreach ($occupiedPositions as $state) {
            if ($state->getX() && $state->getY()) {
                $occupied[] = ['x' => $state->getX(), 'y' => $state->getY()];
            }
        }

        // Initialiser les structures pour l'algorithme
        $visited = [];
        $queue = new \SplPriorityQueue();
        $distances = [];
        $availableMoves = [];

        // Position initiale
        $queue->insert(
            ['x' => $currentX, 'y' => $currentY],
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

            // Si ce n'est pas la position de départ, c'est une case accessible
            if ($current['x'] !== $currentX || $current['y'] !== $currentY) {
                $availableMoves[] = ['x' => $current['x'], 'y' => $current['y']];
            }

            // Explorer les voisins
            foreach ($this->getNeighbors($current['x'], $current['y'], $occupied) as $neighbor) {
                $newDistance = $currentDistance + 1;
                $key = $neighbor['x'].','.$neighbor['y'];

                if (!isset($distances[$key]) || $newDistance < $distances[$key]) {
                    $distances[$key] = $newDistance;
                    $queue->insert($neighbor, -$newDistance); // Priorité négative car SplPriorityQueue trie par plus grande valeur
                }
            }
        }

        return new JsonResponse([
            'availableMoves' => $availableMoves
        ]);
    }
} 