<?php

namespace App\Service;

use App\Entity\Team;
use App\Entity\User;
use App\Entity\Faction;

interface TeamServiceInterface
{
    public function getTeamsForUser(User $user): array;
    public function getTeamById(int $id): ?Team;
    public function createTeam(string $name, Faction $faction, User $user): Team;
    public function updateTeam(Team $team): void;
    public function canAccessTeam(Team $team, User $user): bool;
    public function getAllFactions(): array;
    public function getFactionById(int $id): ?Faction;
} 