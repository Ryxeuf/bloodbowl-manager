<?php

namespace App\Service;

use App\Entity\Skill;
use App\Repository\SkillRepository;

class SkillService implements SkillServiceInterface
{
    public function __construct(
        private SkillRepository $skillRepository
    ) {
    }

    public function getSkillsByCategory(): array
    {
        $skills = $this->skillRepository->findAll();
        
        // Définir l'ordre et les noms des catégories
        $categories = [
            'Général' => ['name' => 'Général', 'skills' => []],
            'Agilité' => ['name' => 'Agilité', 'skills' => []],
            'Passe' => ['name' => 'Passe', 'skills' => []],
            'Force' => ['name' => 'Force', 'skills' => []],
            'Mutation' => ['name' => 'Mutation', 'skills' => []],
            'Trait' => ['name' => 'Trait', 'skills' => []],
        ];
        
        // Grouper les compétences par catégorie
        foreach ($skills as $skill) {
            if ($skill->getType() === 'Compétence') {
                $category = $skill->getCategory();
                if (isset($categories[$category])) {
                    $categories[$category]['skills'][] = $skill;
                }
            } else {
                $categories['Trait']['skills'][] = $skill;
            }
        }
        
        // Trier les compétences par nom dans chaque catégorie
        foreach ($categories as &$category) {
            usort($category['skills'], function($a, $b) {
                return strcmp($a->getName(), $b->getName());
            });
        }
        
        return $categories;
    }
} 