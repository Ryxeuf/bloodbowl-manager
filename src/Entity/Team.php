<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TeamRepository;
use App\State\TeamStateProcessor;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
#[ApiResource(denormalizationContext: ['groups' => ['team:update']], processor: TeamStateProcessor::class)]
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
    private User $user;

    #[ORM\OneToMany(mappedBy: 'team', targetEntity: Player::class, cascade: ['remove', 'persist', 'refresh', 'merge'])]
    #[Groups(['team:update', 'team:read'])]
    private Collection|array $players;

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

    public function addPlayer(Player $player)
    {
        $this->players->add($player);
    }

    public function removePlayer(Player $player)
    {
        if ($this->players->contains($player)) {
            $this->players->removeElement($player);
        }
    }
}
