<?php

namespace App\Controller\Reference;

use App\Entity\Position;
use App\Service\PositionServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/position/{id}/stats', name: 'app_position_stats', methods: ['GET'])]
class GetPositionStatsController extends AbstractController
{
    public function __construct(
        private PositionServiceInterface $positionService
    ) {
    }

    public function __invoke(Position $position): JsonResponse
    {
        return $this->json($this->positionService->getPositionStats($position));
    }
} 