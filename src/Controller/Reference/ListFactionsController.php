<?php

namespace App\Controller\Reference;

use App\Service\FactionServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/factions', name: 'app_factions', methods: ['GET'])]
class ListFactionsController extends AbstractController
{
    public function __construct(
        private FactionServiceInterface $factionService
    ) {
    }

    public function __invoke(): Response
    {
        return $this->render('faction/index.html.twig', [
            'factions' => $this->factionService->getAllFactions(),
        ]);
    }
} 