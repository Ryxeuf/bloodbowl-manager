<?php

namespace App\Service;

use App\Entity\Position;

class PositionService implements PositionServiceInterface
{
    public function getPositionStats(Position $position): array
    {
        $skills = [];
        foreach ($position->getSkills() as $skill) {
            $skills[] = [
                'id' => $skill->getId(),
                'name' => $skill->getName(),
                'description' => $skill->getDescription(),
            ];
        }

        return [
            'movement' => $position->getM(),
            'strength' => $position->getF(),
            'agility' => $position->getAg(),
            'passing' => $position->getCp(),
            'armor' => $position->getAr(),
            'cost' => $position->getCost(),
            'skills' => $skills,
        ];
    }
} 