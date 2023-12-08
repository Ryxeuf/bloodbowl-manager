<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PlayerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
#[ApiResource(normalizationContext: ['groups'=> ['player:read', 'team:read']])]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
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
}
