<?php

namespace App\Service;

use App\Entity\Game;
use App\Entity\Team;
use App\Entity\User;
use App\Repository\GameRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;

class GameService implements GameServiceInterface
{
    public function __construct(
        private GameRepository $gameRepository,
        private TeamRepository $teamRepository,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function getGamesForUser(User $user): array
    {
        return $user->getGames()->toArray();
    }

    public function createGame(Team $homeTeam, Team $awayTeam): Game
    {
        $game = new Game();
        $game->setHomeTeam($homeTeam);
        $game->setAwayTeam($awayTeam);
        $game->setCurrentTurn(1);
        $game->setHomeScore(0);
        $game->setAwayScore(0);

        $this->entityManager->persist($game);
        $this->entityManager->flush();

        return $game;
    }

    public function updateGameScore(Game $game, string $action): void
    {
        switch ($action) {
            case 'score_home':
                $game->setHomeScore($game->getHomeScore() + 1);
                break;
            case 'score_away':
                $game->setAwayScore($game->getAwayScore() + 1);
                break;
            case 'next_turn':
                $game->setCurrentTurn($game->getCurrentTurn() + 1);
                break;
        }

        $this->entityManager->flush();
    }

    public function getTeamsForUser($user): array
    {
        return $this->teamRepository->findBy(['user' => $user]);
    }

    public function getTeamById(int $id): ?Team
    {
        return $this->teamRepository->find($id);
    }
} 