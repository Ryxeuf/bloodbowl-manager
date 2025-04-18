<?php

namespace App\Controller\Game;

use App\Entity\Player;
use App\Entity\Game;
use App\Entity\PlayerGameState;
use App\Repository\PlayerGameStateRepository;
use App\Service\GameLogService;
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
        private EntityManagerInterface $entityManager,
        private GameLogService $gameLogService
    ) {
    }

    private function isAdjacentToOpponent(int $x, int $y, Game $game, Player $player): bool
    {
        return $this->countAdjacentOpponents($x, $y, $game, $player) > 0;
    }

    private function countAdjacentOpponents(int $x, int $y, Game $game, Player $player): int
    {
        $playerStates = $this->playerGameStateRepository->findBy(['game' => $game]);
        $playerTeam = $player->getTeam();
        $count = 0;
        
        foreach ($playerStates as $state) {
            if ($state->getPlayer()->getTeam() !== $playerTeam && 
                $state->getState() === PlayerGameState::STATE_AVAILABLE) {
                
                $opponentX = $state->getX();
                $opponentY = $state->getY();
                
                $dx = abs($x - $opponentX);
                $dy = abs($y - $opponentY);
                
                if ($dx <= 1 && $dy <= 1 && !($dx === 0 && $dy === 0)) {
                    $count++;
                }
            }
        }
        
        return $count;
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
        
        // Vérifier que le déplacement est bien d'une seule case (adjacente)
        if ($dx > 1 || $dy > 1) {
            return new JsonResponse([
                'error' => 'Player can only move to adjacent squares'
            ], Response::HTTP_BAD_REQUEST);
        }
        
        // Vérifier qu'on ne reste pas sur place
        if ($dx === 0 && $dy === 0) {
            return new JsonResponse([
                'error' => 'Player must move to a different position'
            ], Response::HTTP_BAD_REQUEST);
        }
        
        // Une case = 1 point de mouvement, peu importe la direction
        $distance = 1;

        // Vérifier que le joueur a assez de mouvement restant
        if ($playerState->getRemainingMovement() <= 0) {
            return new JsonResponse([
                'error' => 'Not enough remaining movement'
            ], Response::HTTP_BAD_REQUEST);
        }

        // Vérifier si le joueur quitte une case adjacente à un adversaire
        $leavingTackleZone = $this->isAdjacentToOpponent($currentX, $currentY, $game, $player);
        
        if ($leavingTackleZone) {
            // Obtenir la compétence d'agilité du joueur
            $agility = $player->getAg();
            
            // Compter le nombre d'adversaires adjacents à la case d'arrivée
            $adjacentOpponents = $this->countAdjacentOpponents($targetX, $targetY, $game, $player);
            
            // Ajuster la difficulté en fonction du nombre d'adversaires adjacents
            $requiredRoll = $agility + $adjacentOpponents;
            
            // Lancer un D6
            $diceRoll = random_int(1, 6);
            
            // Le jet doit être supérieur ou égal à l'agilité du joueur + le nombre d'adversaires adjacents
            $success = $diceRoll >= $requiredRoll;
            
            if (!$success) {
                // Échec de l'esquive, le joueur tombe dans la case de destination
                $playerState->setX($targetX);
                $playerState->setY($targetY);
                $playerState->setState(PlayerGameState::STATE_PRONE);
                $playerState->completeAction();
                
                // Jet d'armure (2D6) pour voir si le joueur est blessé
                $armorRoll1 = random_int(1, 6);
                $armorRoll2 = random_int(1, 6);
                $armorTotal = $armorRoll1 + $armorRoll2;
                $playerArmor = $player->getAr();
                
                $injuryRoll1 = 0;
                $injuryRoll2 = 0;
                $injuryTotal = 0;
                $injuryResult = null;
                
                // Log pour l'échec d'esquive
                $this->gameLogService->addLog(
                    $game,
                    sprintf('%s (#%d) tente de quitter une zone de tacle mais tombe en (%d,%d)! (Jet: %d, AG: %d, Adversaires adjacents: %d)', 
                        $player->getName() ?: $player->getPosition()->getName(), 
                        $player->getNumber(),
                        $targetX,
                        $targetY,
                        $diceRoll,
                        $agility,
                        $adjacentOpponents
                    ),
                    $player,
                    'movement-failed'
                );
                
                // Si le jet d'armure est supérieur ou égal à l'armure du joueur, on fait un jet de blessure
                if ($armorTotal >= $playerArmor) {
                    $injuryRoll1 = random_int(1, 6);
                    $injuryRoll2 = random_int(1, 6);
                    $injuryTotal = $injuryRoll1 + $injuryRoll2;
                    
                    // Déterminer la blessure en fonction du résultat
                    if ($injuryTotal <= 7) {
                        $playerState->setState(PlayerGameState::STATE_STUNNED);
                        $injuryResult = 'stunned';
                    } elseif ($injuryTotal <= 9) {
                        $playerState->setState(PlayerGameState::STATE_KO);
                        $injuryResult = 'ko';
                    } else {
                        $playerState->setState(PlayerGameState::STATE_DEAD);
                        $injuryResult = 'dead';
                    }
                    
                    // Log pour la blessure
                    $this->gameLogService->addLog(
                        $game,
                        sprintf('%s (#%d) est blessé! (Armure: %d+%d=%d/%d, Blessure: %d+%d=%d, Résultat: %s)', 
                            $player->getName() ?: $player->getPosition()->getName(), 
                            $player->getNumber(),
                            $armorRoll1,
                            $armorRoll2,
                            $armorTotal,
                            $playerArmor,
                            $injuryRoll1,
                            $injuryRoll2,
                            $injuryTotal,
                            $injuryResult
                        ),
                        $player,
                        'injury-' . $injuryResult
                    );
                } else {
                    // Log pour le jet d'armure raté
                    $this->gameLogService->addLog(
                        $game,
                        sprintf('%s (#%d) tombe mais son armure le protège (Armure: %d+%d=%d/%d)', 
                            $player->getName() ?: $player->getPosition()->getName(), 
                            $player->getNumber(),
                            $armorRoll1,
                            $armorRoll2,
                            $armorTotal,
                            $playerArmor
                        ),
                        $player,
                        'armor-save'
                    );
                }
                
                $this->entityManager->flush();
                
                return new JsonResponse([
                    'success' => false,
                    'position' => [
                        'x' => $targetX,
                        'y' => $targetY
                    ],
                    'diceRoll' => $diceRoll,
                    'agility' => $agility,
                    'adjacentOpponents' => $adjacentOpponents,
                    'requiredRoll' => $requiredRoll,
                    'armorRoll' => [
                        'dice1' => $armorRoll1,
                        'dice2' => $armorRoll2,
                        'total' => $armorTotal,
                        'armor' => $playerArmor
                    ],
                    'injuryRoll' => $injuryTotal > 0 ? [
                        'dice1' => $injuryRoll1,
                        'dice2' => $injuryRoll2,
                        'total' => $injuryTotal,
                        'result' => $injuryResult
                    ] : null,
                    'reason' => 'Player failed dodge roll',
                    'newState' => $playerState->getState()
                ], Response::HTTP_OK);
            }
            
            // Log pour l'esquive réussie
            $this->gameLogService->addLog(
                $game,
                sprintf('%s (#%d) esquive avec succès! (Jet: %d, AG: %d)', 
                    $player->getName() ?: $player->getPosition()->getName(), 
                    $player->getNumber(),
                    $diceRoll,
                    $agility
                ),
                $player,
                'movement-dodge'
            );
        }

        // Mettre à jour la position du joueur
        $playerState->setX($targetX);
        $playerState->setY($targetY);
        
        // Réduire le mouvement restant
        $playerState->moveBy($distance);
        
        // Ajouter un log pour ce mouvement
        $this->gameLogService->addLog(
            $game,
            sprintf('%s (#%d) se déplace en (%d,%d)', 
                $player->getName() ?: $player->getPosition()->getName(), 
                $player->getNumber(),
                $targetX, 
                $targetY
            ),
            $player,
            'movement'
        );
        
        $this->entityManager->flush();

        return new JsonResponse([
            'success' => true,
            'position' => [
                'x' => $targetX,
                'y' => $targetY
            ],
            'remainingMovement' => $playerState->getRemainingMovement(),
            'dodgeRoll' => $leavingTackleZone ? $diceRoll : null,
            'agility' => $leavingTackleZone ? $agility : null
        ]);
    }
} 