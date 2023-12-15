<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\TeamRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
#[ApiResource(normalizationContext: ['groups' => ['team:read']], denormalizationContext: ['groups' => ['team:update']])]
class Team
{
    public const TYPE_LEAGUE = 'league';
    public const TYPE_EXHIBITION = 'exhibition';

    public const CATEGORY_ELEVENS = 'elevens';
    public const CATEGORY_SEVENS = 'sevens';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['team:update', 'team:read'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['team:update', 'team:read'])]
    private int $rerolls = 0;

    #[ORM\Column]
    #[Groups(['team:update', 'team:read'])]
    private int $assistantCoaches = 0;

    #[ORM\Column]
    #[Groups(['team:update', 'team:read'])]
    private int $cheerleaders = 0;

    #[ORM\Column]
    #[Groups(['team:update', 'team:read'])]
    private int $dedicatedFans = 0;

    #[ORM\Column]
    #[Groups(['team:update', 'team:read'])]
    private int $apothecary = 0;

    #[ORM\Column(length: 255)]
    #[Groups(['team:update', 'team:read'])]
    private ?string $playType = self::TYPE_LEAGUE;

    #[ORM\Column(length: 255)]
    #[Groups(['team:update', 'team:read'])]
    private ?string $playCategory = self::CATEGORY_ELEVENS;

    #[ORM\ManyToOne(targetEntity: Faction::class, inversedBy: 'teams')]
    #[Groups(['team:update', 'team:read'])]
    private Faction $faction;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'teams')]
    #[Groups(['team:update', 'team:read'])]
    private User $user;

    /** @var Collection|array|Player[]  */
    #[ORM\OneToMany(mappedBy: 'team', targetEntity: Player::class, cascade: ['remove', 'persist', 'refresh', 'merge'])]
    #[ORM\OrderBy(['number' => 'ASC'])]
    #[Groups(['team:update', 'team:read'])]
    private Collection|array $players;

    #[ApiProperty]
    #[Groups(['team:read'])]
    public function getTreasury(): int
    {
        $rest = $this->playCategory === self::CATEGORY_ELEVENS ? 1_000_000 : 600_000;
        foreach ($this->players as $player) {
            $rest -= $player->getPosition()->getCost();
        }
        $rest -= $this->getCheerleaders() * $this->getCheerleadersCost();
        $rest -= $this->getDedicatedFans() * $this->getDedicatedFansCost();
        $rest -= $this->getAssistantCoaches() * $this->getAssistantCoachesCost();
        $rest -= $this->getApothecary() * $this->getApothecaryCost();

        return $rest;
    }

    #[ApiProperty]
    #[Groups(['team:read'])]
    public function getTeamValue(): int
    {
        $rest = 0;
        foreach ($this->players as $player) {
            $rest += $player->getPosition()->getCost();
        }

        return $rest;
    }

    #[ApiProperty]
    #[Groups(['team:read'])]
    public function getCheerleadersCost(): int
    {
        return $this->faction->getCheerleadersCost() * ($this->playCategory === self::CATEGORY_ELEVENS ? 1 : 2);
    }

    #[ApiProperty]
    #[Groups(['team:read'])]
    public function getDedicatedFansCost(): int
    {
        return $this->faction->getDedicatedFansCost() * ($this->playCategory === self::CATEGORY_ELEVENS ? 1 : 2);
    }

    #[ApiProperty]
    #[Groups(['team:read'])]
    public function getAssistantCoachesCost(): int
    {
        return $this->faction->getAssistantCoachesCost() * ($this->playCategory === self::CATEGORY_ELEVENS ? 1 : 2);
    }

    #[ApiProperty]
    #[Groups(['team:read'])]
    public function getCheerleadersMax(): int
    {
        return $this->faction->getCheerleadersMax();
    }

    #[ApiProperty]
    #[Groups(['team:read'])]
    public function getDedicatedFansMax(): int
    {
        return $this->faction->getDedicatedFansMax();
    }

    #[ApiProperty]
    #[Groups(['team:read'])]
    public function getAssistantCoachesMax(): int
    {
        return $this->faction->getAssistantCoachesMax();
    }

    #[ApiProperty]
    #[Groups(['team:read'])]
    public function getRerollMax(): int
    {
        return $this->faction->getRerollMax();
    }

    #[ApiProperty]
    #[Groups(['team:read'])]
    public function getApothecaryMax(): int
    {
        return $this->faction->hasApothecary() ? 1 : 0;
    }

    #[ApiProperty]
    #[Groups(['team:read'])]
    public function getRerollCost(): int
    {
        return $this->faction->getRerollCost() * ($this->playCategory === self::CATEGORY_ELEVENS ? 1 : 2);
    }

    #[ApiProperty]
    #[Groups(['team:read'])]
    public function getApothecaryCost(): int
    {
        return $this->playCategory === self::CATEGORY_ELEVENS ? 50_000 : 80_000;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPlayType(): ?string
    {
        return $this->playType;
    }

    public function setPlayType(string $playType): static
    {
        $this->playType = $playType;

        return $this;
    }

    public function getPlayCategory(): ?string
    {
        return $this->playCategory;
    }

    public function setPlayCategory(string $playCategory): static
    {
        $this->playCategory = $playCategory;

        return $this;
    }

    public function getFaction(): Faction
    {
        return $this->faction;
    }

    public function setFaction(Faction $faction): void
    {
        $this->faction = $faction;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getPlayers(): Collection|array
    {
        return $this->players;
    }

    public function setPlayers(Collection|array $players): void
    {
        $this->players = $players;
    }

    public function addPlayer(Player $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players->add($player);
            $player->setTeam($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        if ($this->players->contains($player)) {
            $this->players->removeElement($player);
            $player->setTeam($this);
        }

        return $this;
    }

    public function getRerolls(): int
    {
        return $this->rerolls;
    }

    public function setRerolls(int $rerolls): void
    {
        $this->rerolls = $rerolls;
    }

    public function getAssistantCoaches(): int
    {
        return $this->assistantCoaches;
    }

    public function setAssistantCoaches(int $assistantCoaches): void
    {
        $this->assistantCoaches = $assistantCoaches;
    }

    public function getCheerleaders(): int
    {
        return $this->cheerleaders;
    }

    public function setCheerleaders(int $cheerleaders): void
    {
        $this->cheerleaders = $cheerleaders;
    }

    public function getDedicatedFans(): int
    {
        return $this->dedicatedFans;
    }

    public function setDedicatedFans(int $dedicatedFans): void
    {
        $this->dedicatedFans = $dedicatedFans;
    }

    public function getApothecary(): int
    {
        return $this->apothecary;
    }

    public function setApothecary(int $apothecary): void
    {
        $this->apothecary = $apothecary;
    }
}
