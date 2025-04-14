<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\PlayerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
#[ApiResource(normalizationContext: ['groups' => ['player:read', 'team:read']])]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['team:update', 'player:read', 'team:read'])]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['team:update', 'player:read', 'team:read'])]
    private ?int $number = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['team:update', 'player:read', 'team:read'])]
    private ?string $name = null;

    #[ORM\ManyToOne(targetEntity: Team::class, inversedBy: 'players')]
    private Team $team;

    #[ORM\ManyToOne(targetEntity: Position::class, inversedBy: 'players')]
    #[Groups(['team:update', 'player:read', 'team:read'])]
    private Position $position;

    #[ORM\OneToMany(mappedBy: 'player', targetEntity: PlayerGameState::class)]
    private Collection $gameStates;

    public function __construct()
    {
        $this->gameStates = new ArrayCollection();
    }

    #[ApiProperty]
    #[Groups(['team:update', 'player:read', 'team:read'])]
    public function getPositionInfos(): array
    {
        return [
            'name' => $this->position->getName(),
            'm'    => $this->position->getM(),
            'f'    => $this->position->getF(),
            'ag'   => $this->position->getAg(),
            'cp'   => $this->position->getCp(),
            'ar'   => $this->position->getAr(),
            'cost' => $this->position->getCost(),
            'skills' => $this->position->getSkills(),
            'primarySkills' => $this->position->getPrimarySkills(),
            'secondarySkills' => $this->position->getSecondarySkills(),
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeam(): Team
    {
        return $this->team;
    }

    public function setTeam(Team $team): void
    {
        $this->team = $team;
    }

    public function getPosition(): Position
    {
        return $this->position;
    }

    public function setPosition(Position $position): void
    {
        $this->position = $position;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): void
    {
        $this->number = $number;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getFaction(): Faction
    {
        return $this->team->getFaction();
    }

    public function getF(): ?int
    {
        return $this->position->getF();
    }

    public function getM(): ?int
    {
        return $this->position->getM();
    }

    public function getAg(): ?int
    {
        return $this->position->getAg();
    }

    public function getCp(): int|string
    {
        return $this->position->getCp();
    }

    public function getAr(): ?int
    {
        return $this->position->getAr();
    }

    public function getCost(): ?int
    {
        return $this->position->getCost();
    }

    public function getPositionSkills(): array
    {
        return $this->position->getSkills();
    }

    public function getPositionPrimarySkills(): array
    {
        return $this->position->getPrimarySkills();
    }

    public function getPositionSecondarySkills(): array
    {
        return $this->position->getSecondarySkills();
    }

    public function getGameStates(): Collection
    {
        return $this->gameStates;
    }

    public function addGameState(PlayerGameState $gameState): self
    {
        if (!$this->gameStates->contains($gameState)) {
            $this->gameStates[] = $gameState;
            $gameState->setPlayer($this);
        }
        return $this;
    }

    public function removeGameState(PlayerGameState $gameState): self
    {
        $this->gameStates->removeElement($gameState);
        return $this;
    }
}
