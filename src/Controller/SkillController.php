<?php

namespace App\Controller;

use App\Repository\SkillRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SkillController extends AbstractController
{
    #[Route('/competences', name: 'app_skills')]
    public function index(SkillRepository $skillRepository): Response
    {
        $skills = $skillRepository->findAll();
        
        // Définir l'ordre et les noms des catégories
        $categories = [
            'Général' => ['name' => 'Général', 'skills' => []],
            'Agilité' => ['name' => 'Agilité', 'skills' => []],
            'Passe' => ['name' => 'Passe', 'skills' => []],
            'Force' => ['name' => 'Force', 'skills' => []],
            'Mutation' => ['name' => 'Mutation', 'skills' => []],
        ];
        
        // Grouper les compétences par catégorie
        foreach ($skills as $skill) {
            $category = $skill->getCategory();
            if (isset($categories[$category])) {
                $categories[$category]['skills'][] = $skill;
            }
        }
        
        // Trier les compétences par nom dans chaque catégorie
        foreach ($categories as &$category) {
            usort($category['skills'], function($a, $b) {
                return strcmp($a->getName(), $b->getName());
            });
        }
        
        return $this->render('skill/index.html.twig', [
            'categories' => $categories,
        ]);
    }
} 