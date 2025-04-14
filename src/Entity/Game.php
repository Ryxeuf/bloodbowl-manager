<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'homeGames')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Team $homeTeam = null;

    #[ORM\ManyToOne(inversedBy: 'awayGames')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Team $awayTeam = null;

    #[ORM\Column]
    private ?int $currentTurn = 1;

    #[ORM\Column]
    private ?int $homeScore = 0;

    #[ORM\Column]
    private ?int $awayScore = 0;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: PlayerGameState::class, cascade: ['persist', 'remove'])]
    private Collection $playerStates;

    public function __construct()
    {
        $this->playerStates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHomeTeam(): ?Team
    {
        return $this->homeTeam;
    }

    public function setHomeTeam(?Team $homeTeam): static
    {
        $this->homeTeam = $homeTeam;

        return $this;
    }

    public function getAwayTeam(): ?Team
    {
        return $this->awayTeam;
    }

    public function setAwayTeam(?Team $awayTeam): static
    {
        $this->awayTeam = $awayTeam;

        return $this;
    }

    public function getCurrentTurn(): ?int
    {
        return $this->currentTurn;
    }

    public function setCurrentTurn(int $currentTurn): static
    {
        $this->currentTurn = $currentTurn;

        return $this;
    }

    public function getHomeScore(): ?int
    {
        return $this->homeScore;
    }

    public function setHomeScore(int $homeScore): static
    {
        $this->homeScore = $homeScore;

        return $this;
    }

    public function getAwayScore(): ?int
    {
        return $this->awayScore;
    }

    public function setAwayScore(int $awayScore): static
    {
        $this->awayScore = $awayScore;

        return $this;
    }

    public function getPlayerStates(): Collection
    {
        return $this->playerStates;
    }

    public function addPlayerState(PlayerGameState $playerState): self
    {
        if (!$this->playerStates->contains($playerState)) {
            $this->playerStates[] = $playerState;
            $playerState->setGame($this);
        }
        return $this;
    }

    public function removePlayerState(PlayerGameState $playerState): self
    {
        $this->playerStates->removeElement($playerState);
        return $this;
    }
}
