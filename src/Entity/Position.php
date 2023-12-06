<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\PositionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PositionRepository::class)]
#[ApiResource]
class Position
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private string $name;

    #[ORM\Column]
    private int $min = 0;

    #[ORM\Column]
    private int $max = 1;

    #[ORM\Column]
    private ?int $m;

    #[ORM\Column]
    private ?int $f;

    #[ORM\Column]
    private ?int $ag;

    #[ORM\Column(nullable: true)]
    private ?int $cp = null;

    #[ORM\Column]
    private ?int $ar;

    #[ORM\Column]
    private int $cost;

    #[ORM\ManyToOne(targetEntity: Faction::class, inversedBy: 'positions')]
    private Faction $faction;

    #[ORM\ManyToMany(targetEntity: Skill::class, inversedBy: "positions")]
    private Collection|array $skills;

    public function __construct() {
        $this->skills = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getMin(): int
    {
        return $this->min;
    }

    public function setMin(int $min): void
    {
        $this->min = $min;
    }

    public function getMax(): int
    {
        return $this->max;
    }

    public function setMax(int $max): void
    {
        $this->max = $max;
    }

    public function getM(): ?int
    {
        return $this->m;
    }

    public function setM(?int $m): void
    {
        $this->m = $m;
    }

    public function getF(): ?int
    {
        return $this->f;
    }

    public function setF(?int $f): void
    {
        $this->f = $f;
    }

    public function getAg(): ?int
    {
        return $this->ag;
    }

    public function setAg(?int $ag): void
    {
        $this->ag = $ag;
    }

    public function getCp(): ?int
    {
        return $this->cp;
    }

    public function setCp(?int $cp): void
    {
        $this->cp = $cp;
    }

    public function getAr(): ?int
    {
        return $this->ar;
    }

    public function setAr(?int $ar): void
    {
        $this->ar = $ar;
    }

    public function getFaction(): Faction
    {
        return $this->faction;
    }

    public function setFaction(Faction $faction): void
    {
        $this->faction = $faction;
    }

    public function getSkills(): Collection|array
    {
        return $this->skills;
    }

    public function setSkills(Collection|array $skills): void
    {
        $this->skills = $skills;
    }

    public function addSkill(Skill $skill): void
    {
        $this->skills->add($skill);
    }

    public function getCost(): int
    {
        return $this->cost;
    }

    public function setCost(int $cost): void
    {
        $this->cost = $cost;
    }

    #[ApiProperty]
    public function getQuantity(): string
    {
        return $this->min . '-' . $this->max;
    }
}
