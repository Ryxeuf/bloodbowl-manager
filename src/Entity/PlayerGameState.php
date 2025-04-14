<?php

namespace App\Entity;

use App\Repository\PlayerGameStateRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: PlayerGameStateRepository::class)]
#[ApiResource]
#[ORM\UniqueConstraint(name: 'unique_position_per_game', columns: ['x', 'y', 'game_id'])]
#[ORM\UniqueConstraint(name: 'unique_player_per_game', columns: ['player_id', 'game_id'])]
class PlayerGameState
{
    public const STATE_AVAILABLE = 'available';
    public const STATE_PRONE = 'prone';
    public const STATE_STUNNED = 'stunned';
    public const STATE_KO = 'ko';
    public const STATE_INJURED = 'injured';
    public const STATE_DEAD = 'dead';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Player::class, inversedBy: 'gameStates')]
    #[ORM\JoinColumn(nullable: false)]
    private Player $player;

    #[ORM\ManyToOne(targetEntity: Game::class, inversedBy: 'playerStates')]
    #[ORM\JoinColumn(nullable: false)]
    private Game $game;

    #[ORM\Column(length: 255)]
    private string $state = self::STATE_AVAILABLE;

    #[ORM\Column(nullable: true)]
    private ?int $x = null;

    #[ORM\Column(nullable: true)]
    private ?int $y = null;

    #[ORM\Column]
    private bool $hasPlayed = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function setPlayer(Player $player): self
    {
        $this->player = $player;
        return $this;
    }

    public function getGame(): Game
    {
        return $this->game;
    }

    public function setGame(Game $game): self
    {
        $this->game = $game;
        return $this;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;
        return $this;
    }

    public function getX(): ?int
    {
        return $this->x;
    }

    public function setX(?int $x): self
    {
        $this->x = $x;
        return $this;
    }

    public function getY(): ?int
    {
        return $this->y;
    }

    public function setY(?int $y): self
    {
        $this->y = $y;
        return $this;
    }

    public function hasPlayed(): bool
    {
        return $this->hasPlayed;
    }

    public function setHasPlayed(bool $hasPlayed): self
    {
        $this->hasPlayed = $hasPlayed;
        return $this;
    }
} 