<?php

namespace App\Controller\Player;

use App\Entity\Player;
use App\Entity\Game;
use App\Entity\PlayerGameState;
use App\Repository\PlayerRepository;
use App\Repository\PlayerGameStateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/player/{player}/info', name: 'app_player_info', methods: ['GET'])]
class PlayerInfoController extends AbstractController
{
    public function __construct(
        private PlayerRepository $playerRepository,
        private PlayerGameStateRepository $playerGameStateRepository
    ) {
    }

    public function __invoke(Request $request, Player $player): Response
    {
        // Récupérer l'ID du jeu à partir de la requête
        $gameId = $request->query->get('gameId');
        
        // Par défaut, on considère que le joueur n'appartient pas à l'équipe active
        $isPlayerTeamActive = false;
        $isPlayerAvailable = false;
        
        // Actions possibles du joueur (par défaut toutes à false)
        $canMove = false;
        $canBlock = false;
        $canPass = false;
        $canHandoff = false;
        $canFoul = false;
        $canBlitz = false;
        
        if ($gameId) {
            // Récupérer l'état du joueur dans ce jeu
            $playerState = $this->playerGameStateRepository->findOneBy([
                'game' => $gameId,
                'player' => $player
            ]);
            
            if ($playerState) {
                // Récupérer le jeu
                $game = $playerState->getGame();
                
                // Vérifier si l'équipe du joueur est l'équipe active
                $activeTeam = ($game->getCurrentTurn() % 2 === 1) ? $game->getHomeTeam() : $game->getAwayTeam();
                $isPlayerTeamActive = ($player->getTeam() === $activeTeam);
                
                // Vérifier si le joueur est disponible
                $isPlayerAvailable = ($playerState->getState() === PlayerGameState::STATE_AVAILABLE);

                $hasPlayed = $playerState->hasPlayed();
                
                // Déterminer les actions possibles
                if ($isPlayerTeamActive && $isPlayerAvailable) {
                    // Version très simplifiée pour la démonstration
                    // Dans un cas réel, cette logique serait plus complexe et basée
                    // sur l'état du jeu, l'état du joueur, sa position, etc.
                    
                    // Pour la démonstration, on active toutes les actions de base
                    // quand le joueur est dans l'équipe active et disponible
                    $canMove = true;
                    $canBlock = true;
                    $canPass = true;
                    $canHandoff = true;
                    $canFoul = true;
                    $canBlitz = true;
                }
            }
        }
        
        return $this->render('game/player_info.html.twig', [
            'player' => $player,
            'playerState' => $playerState,
            'isPlayerTeamActive' => $isPlayerTeamActive,
            'isPlayerAvailable' => $isPlayerAvailable,
            'hasPlayed' => $hasPlayed,
            'actions' => [
                'canMove' => $canMove,
                'canBlock' => $canBlock,
                'canPass' => $canPass,
                'canHandoff' => $canHandoff,
                'canFoul' => $canFoul,
                'canBlitz' => $canBlitz
            ]
        ]);
    }
} 