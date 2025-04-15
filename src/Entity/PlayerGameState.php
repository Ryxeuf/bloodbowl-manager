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

    // Actions possibles pour un joueur
    public const ACTION_NONE = 'none';
    public const ACTION_MOVE = 'move';
    public const ACTION_BLOCK = 'block';
    public const ACTION_BLITZ = 'blitz';
    public const ACTION_PASS = 'pass';
    public const ACTION_HANDOFF = 'handoff';
    public const ACTION_FOUL = 'foul';

    // Statuts des actions
    public const ACTION_STATUS_NOT_STARTED = 'not_started';
    public const ACTION_STATUS_IN_PROGRESS = 'in_progress';
    public const ACTION_STATUS_COMPLETED = 'completed';

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

    #[ORM\Column(length: 255)]
    private string $currentAction = self::ACTION_NONE;

    #[ORM\Column(length: 255)]
    private string $actionStatus = self::ACTION_STATUS_NOT_STARTED;

    #[ORM\Column]
    private int $remainingMovement = 0;

    #[ORM\Column]
    private bool $hasBall = false;

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

    public function getCurrentAction(): string
    {
        return $this->currentAction;
    }

    public function setCurrentAction(string $action): self
    {
        $this->currentAction = $action;
        return $this;
    }

    public function getActionStatus(): string
    {
        return $this->actionStatus;
    }

    public function setActionStatus(string $status): self
    {
        $this->actionStatus = $status;
        return $this;
    }

    public function getRemainingMovement(): int
    {
        return $this->remainingMovement;
    }

    public function setRemainingMovement(int $movement): self
    {
        $this->remainingMovement = $movement;
        return $this;
    }

    public function hasBall(): bool
    {
        return $this->hasBall;
    }

    public function setHasBall(bool $hasBall): self
    {
        $this->hasBall = $hasBall;
        return $this;
    }

    public function startAction(string $action): self
    {
        $this->currentAction = $action;
        $this->actionStatus = self::ACTION_STATUS_IN_PROGRESS;
        
        // Si c'est une action de mouvement, on initialise le mouvement restant
        if ($action === self::ACTION_MOVE || $action === self::ACTION_BLITZ) {
            $this->remainingMovement = $this->player->getM();
        } else if ($action === self::ACTION_PASS || $action === self::ACTION_HANDOFF) {
            // Pour les actions de passe ou transmission, on donne un mouvement limité
            $this->remainingMovement = min(6, $this->player->getM());
        }
        
        return $this;
    }

    public function completeAction(): self
    {
        $this->actionStatus = self::ACTION_STATUS_COMPLETED;
        $this->remainingMovement = 0;
        return $this;
    }

    public function resetAction(): self
    {
        $this->currentAction = self::ACTION_NONE;
        $this->actionStatus = self::ACTION_STATUS_NOT_STARTED;
        $this->remainingMovement = 0;
        return $this;
    }

    public function moveBy(int $distance): self
    {
        $this->remainingMovement = max(0, $this->remainingMovement - $distance);
        return $this;
    }
} 