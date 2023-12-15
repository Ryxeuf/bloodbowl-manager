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
    private int $assistantCoachesMin = 0;

    #[ORM\Column]
    private int $assistantCoachesMax = 6;

    #[ORM\Column]
    private int $assistantCoachesCost = 10_000;

    #[ORM\Column]
    private int $cheerleadersMin  = 0;

    #[ORM\Column]
    private int $cheerleadersMax = 12;

    #[ORM\Column]
    private int $cheerleadersCost  = 10_000;

    #[ORM\Column]
    private int $dedicatedFansMin = 0;

    #[ORM\Column]
    private int $dedicatedFansMax  = 5;

    #[ORM\Column]
    private int $dedicatedFansCost = 10_000;

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

    #[ApiProperty]
    public function getQuantityRerolls(): string
    {
        return $this->rerollMin . '-' . $this->rerollMax;
    }

    public function __construct()
    {
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

    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function setTeams(Collection $teams): void
    {
        $this->teams = $teams;
    }

    public function getAssistantCoachesMin(): int
    {
        return $this->assistantCoachesMin;
    }

    public function setAssistantCoachesMin(int $assistantCoachesMin): void
    {
        $this->assistantCoachesMin = $assistantCoachesMin;
    }

    public function getAssistantCoachesMax(): int
    {
        return $this->assistantCoachesMax;
    }

    public function setAssistantCoachesMax(int $assistantCoachesMax): void
    {
        $this->assistantCoachesMax = $assistantCoachesMax;
    }

    public function getAssistantCoachesCost(): int
    {
        return $this->assistantCoachesCost;
    }

    public function setAssistantCoachesCost(int $assistantCoachesCost): void
    {
        $this->assistantCoachesCost = $assistantCoachesCost;
    }

    public function getCheerleadersMin(): int
    {
        return $this->cheerleadersMin;
    }

    public function setCheerleadersMin(int $cheerleadersMin): void
    {
        $this->cheerleadersMin = $cheerleadersMin;
    }

    public function getDedicatedFansMin(): int
    {
        return $this->dedicatedFansMin;
    }

    public function setDedicatedFansMin(int $dedicatedFansMin): void
    {
        $this->dedicatedFansMin = $dedicatedFansMin;
    }

    public function getCheerleadersMax(): int
    {
        return $this->cheerleadersMax;
    }

    public function setCheerleadersMax(int $cheerleadersMax): void
    {
        $this->cheerleadersMax = $cheerleadersMax;
    }

    public function getDedicatedFansMax(): int
    {
        return $this->dedicatedFansMax;
    }

    public function setDedicatedFansMax(int $dedicatedFansMax): void
    {
        $this->dedicatedFansMax = $dedicatedFansMax;
    }

    public function getCheerleadersCost(): int
    {
        return $this->cheerleadersCost;
    }

    public function setCheerleadersCost(int $cheerleadersCost): void
    {
        $this->cheerleadersCost = $cheerleadersCost;
    }

    public function getDedicatedFansCost(): int
    {
        return $this->dedicatedFansCost;
    }

    public function setDedicatedFansCost(int $dedicatedFansCost): void
    {
        $this->dedicatedFansCost = $dedicatedFansCost;
    }
}
