<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\FactionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FactionRepository::class)]
#[ApiResource]
class Faction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private string $name;

    #[ORM\Column]
    private int $rerollMin = 0;

    #[ORM\Column]
    private int $rerollMax = 0;

    #[ORM\Column]
    private int $rerollCost = 0;

    #[ORM\Column]
    private int $tier = 0;

    #[ORM\Column]
    private bool $apothecary = false;

    #[ORM\OneToMany(mappedBy: 'faction', targetEntity: Position::class)]
    private Collection $positions;

    #[ORM\OneToMany(mappedBy: 'faction', targetEntity: Team::class)]
    private Collection $teams;

    #[ORM\ManyToMany(targetEntity: TeamSpecialRule::class, inversedBy: "factions")]
    private Collection|array $specialRules;

    public function __construct() {
        $this->positions = new ArrayCollection();
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

    public function getPositions(): Collection
    {
        return $this->positions;
    }

    public function setPositions(Collection $positions): void
    {
        $this->positions = $positions;
    }

    public function getRerollMin(): int
    {
        return $this->rerollMin;
    }

    public function setRerollMin(int $rerollMin): void
    {
        $this->rerollMin = $rerollMin;
    }

    public function getRerollMax(): int
    {
        return $this->rerollMax;
    }

    public function setRerollMax(int $rerollMax): void
    {
        $this->rerollMax = $rerollMax;
    }

    public function getRerollCost(): int
    {
        return $this->rerollCost;
    }

    public function setRerollCost(int $rerollCost): void
    {
        $this->rerollCost = $rerollCost;
    }

    public function getTier(): int
    {
        return $this->tier;
    }

    public function setTier(int $tier): void
    {
        $this->tier = $tier;
    }

    public function hasApothecary(): bool
    {
        return $this->apothecary;
    }

    public function setApothecary(bool $apothecary): void
    {
        $this->apothecary = $apothecary;
    }

    public function getSpecialRules(): Collection|array
    {
        return $this->specialRules;
    }

    public function setSpecialRules(Collection|array $specialRules): void
    {
        $this->specialRules = $specialRules;
    }

    #[ApiProperty]
    public function getQuantityRerolls(): string
    {
        return $this->rerollMin . '-' . $this->rerollMax;
    }

    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function setTeams(Collection $teams): void
    {
        $this->teams = $teams;
    }
}
