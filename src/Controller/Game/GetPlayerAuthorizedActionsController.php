<?php

namespace App\Controller\Game;

use App\Entity\Player;
use App\Entity\Game;
use App\Entity\PlayerGameState;
use App\Repository\PlayerGameStateRepository;
use App\Security\Voter\PlayerActionVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/game/{game}/player/{player}/authorized-actions', name: 'app_game_player_authorized_actions', methods: ['GET'])]
class GetPlayerAuthorizedActionsController extends AbstractController
{
    private const ACTION_MAPPING = [
        PlayerGameState::ACTION_MOVE => PlayerActionVoter::ACTION_MOVE,
        PlayerGameState::ACTION_BLOCK => PlayerActionVoter::ACTION_BLOCK,
        PlayerGameState::ACTION_BLITZ => PlayerActionVoter::ACTION_BLITZ,
        PlayerGameState::ACTION_PASS => PlayerActionVoter::ACTION_PASS,
        PlayerGameState::ACTION_HANDOFF => PlayerActionVoter::ACTION_HANDOFF,
        PlayerGameState::ACTION_FOUL => PlayerActionVoter::ACTION_FOUL,
    ];

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

        // Vérifier les actions autorisées
        $authorizedActions = [];
        
        foreach (self::ACTION_MAPPING as $actionKey => $voterAttribute) {
            $authorizedActions[$actionKey] = $this->isGranted($voterAttribute, $playerState);
        }

        return new JsonResponse([
            'player_id' => $player->getId(),
            'game_id' => $game->getId(),
            'authorized_actions' => $authorizedActions
        ]);
    }
} 