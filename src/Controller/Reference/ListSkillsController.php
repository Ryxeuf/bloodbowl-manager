<?php

namespace App\Controller\Reference;

use App\Service\SkillServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/competences', name: 'app_skills', methods: ['GET'])]
class ListSkillsController extends AbstractController
{
    public function __construct(
        private SkillServiceInterface $skillService
    ) {
    }

    public function __invoke(): Response
    {
        return $this->render('skill/index.html.twig', [
            'categories' => $this->skillService->getSkillsByCategory(),
        ]);
    }
} 