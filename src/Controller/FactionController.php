<?php

namespace App\Controller;

use App\Entity\Faction;
use App\Repository\FactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FactionController extends AbstractController
{
    #[Route('/factions', name: 'app_factions')]
    public function index(FactionRepository $factionRepository): Response
    {
        return $this->render('faction/index.html.twig', [
            'factions' => $factionRepository->findAll(),
        ]);
    }

    #[Route('/factions/{id}', name: 'app_faction_show')]
    public function show(Faction $faction): Response
    {
        return $this->render('faction/show.html.twig', [
            'faction' => $faction,
        ]);
    }
} 