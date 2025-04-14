<?php

namespace App\Service;

use App\Entity\Faction;
use App\Repository\FactionRepository;

class FactionService implements FactionServiceInterface
{
    public function __construct(
        private FactionRepository $factionRepository
    ) {
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