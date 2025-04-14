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

        $currentX = $playerState->getX();
        $currentY = $playerState->getY();

        // Récupérer toutes les positions occupées
        $occupiedPositions = $this->playerGameStateRepository->findBy(['game' => $game]);
        $occupied = [];
        foreach ($occupiedPositions as $state) {
            if ($state->getX() && $state->getY()) {
                $occupied[] = ['x' => $state->getX(), 'y' => $state->getY()];
            }
        }

        // Calculer les cases disponibles (dans un rayon de 6 cases)
        $availableMoves = [];
        for ($x = max(1, $currentX - 6); $x <= min(26, $currentX + 6); $x++) {
            for ($y = max(1, $currentY - 6); $y <= min(15, $currentY + 6); $y++) {
                // Vérifier si la case est dans le rayon de mouvement
                $dx = abs($x - $currentX);
                $dy = abs($y - $currentY);
                $distance = max($dx, $dy); // Chaque déplacement coûte 1 case
                
                if ($distance <= 6) {
                    // Vérifier si la case est occupée
                    $isOccupied = false;
                    foreach ($occupied as $pos) {
                        if ($pos['x'] === $x && $pos['y'] === $y) {
                            $isOccupied = true;
                            break;
                        }
                    }
                    if (!$isOccupied) {
                        $availableMoves[] = ['x' => $x, 'y' => $y];
                    }
                }
            }
        }

        return new JsonResponse([
            'availableMoves' => $availableMoves
        ]);
    }
} 