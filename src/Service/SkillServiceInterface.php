<?php

namespace App\Service;

use App\Entity\Skill;

interface SkillServiceInterface
{
    public function getSkillsByCategory(): array;
} 