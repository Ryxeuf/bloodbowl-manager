<?php

namespace App\Controller;

use App\Entity\Position;
use App\Repository\PositionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/position')]
class PositionController extends AbstractController
{
    #[Route('/{id}/stats', name: 'app_position_stats', methods: ['GET'])]
    public function getStats(Position $position): JsonResponse
    {
        $skills = [];
        foreach ($position->getSkills() as $skill) {
            $skills[] = [
                'id' => $skill->getId(),
                'name' => $skill->getName(),
                'description' => $skill->getDescription(),
            ];
        }

        return $this->json([
            'movement' => $position->getM(),
            'strength' => $position->getF(),
            'agility' => $position->getAg(),
            'passing' => $position->getCp(),
            'armor' => $position->getAr(),
            'cost' => $position->getCost(),
            'skills' => $skills,
        ]);
    }
} 