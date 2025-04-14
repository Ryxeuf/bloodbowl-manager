<?php

namespace App\Service;

use App\Entity\Faction;

interface FactionServiceInterface
{
    public function getAllFactions(): array;
    public function getFactionById(int $id): ?Faction;
} 