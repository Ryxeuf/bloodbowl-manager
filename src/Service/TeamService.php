<?php

namespace App\Service;

use App\Entity\Team;
use App\Entity\User;
use App\Entity\Faction;
use App\Repository\TeamRepository;
use App\Repository\FactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

class TeamService implements TeamServiceInterface
{
    public function __construct(
        private TeamRepository $teamRepository,
        private FactionRepository $factionRepository,
        private EntityManagerInterface $entityManager,
        private RoleHierarchyInterface $roleHierarchy
    ) {
    }

    public function getTeamsForUser(User $user): array
    {
        return $this->teamRepository->findBy(['user' => $user]);
    }

    public function getTeamById(int $id): ?Team
    {
        return $this->teamRepository->find($id);
    }

    public function createTeam(string $name, Faction $faction, User $user): Team
    {
        $team = new Team();
        $team->setName($name);
        $team->setFaction($faction);
        $team->setUser($user);

        $this->entityManager->persist($team);
        $this->entityManager->flush();

        return $team;
    }

    public function updateTeam(Team $team): void
    {
        $this->entityManager->flush();
    }

    public function canAccessTeam(Team $team, User $user): bool
    {
        return $team->getUser() === $user || in_array('ROLE_ADMIN', $this->roleHierarchy->getReachableRoleNames($user->getRoles()));
    }

    public function getAllFactions(): array
    {
        return $this->factionRepository->findAll();
    }

    public function getFactionById(int $id): ?Faction
    {
        return $this->factionRepository->find($id);
    }
} 