<?php

namespace App\Service;

use App\Entity\Game;
use App\Entity\Team;
use App\Entity\User;

interface GameServiceInterface
{
    public function getGamesForUser(User $user): array;
    public function createGame(Team $homeTeam, Team $awayTeam): Game;
    public function updateGameScore(Game $game, string $action): void;
    public function getTeamsForUser($user): array;
    public function getTeamById(int $id): ?Team;
} 