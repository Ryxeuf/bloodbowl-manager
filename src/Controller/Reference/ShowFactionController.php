<?php

namespace App\Controller\Reference;

use App\Entity\Faction;
use App\Service\FactionServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/factions/{id}', name: 'app_faction_show', methods: ['GET'])]
class ShowFactionController extends AbstractController
{
    public function __construct(
        private FactionServiceInterface $factionService
    ) {
    }

    public function __invoke(Faction $faction): Response
    {
        return $this->render('faction/show.html.twig', [
            'faction' => $faction,
        ]);
    }
} 