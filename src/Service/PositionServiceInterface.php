<?php

namespace App\Service;

use App\Entity\Position;

interface PositionServiceInterface
{
    public function getPositionStats(Position $position): array;
} 