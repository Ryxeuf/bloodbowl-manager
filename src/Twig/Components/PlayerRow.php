<?php

namespace App\Twig\Components;

use App\Entity\Player;
use App\Entity\Position;
use App\Repository\PositionRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Team;

#[AsLiveComponent('player_row')]
final class PlayerRow
{
    use DefaultActionTrait;

    #[LiveProp(writable: ['name', 'number'])]
    public Player $player;

    #[LiveProp]
    public bool $isEditing = false;

    #[LiveProp]
    public bool $isNew = false;

    #[LiveProp]
    public ?int $factionId = null;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private PositionRepository $positionRepository
    ) {
    }

    public function mount(Player $player): void
    {
        $this->player = $player;
    }

    #[LiveAction]
    public function updateName(string $name): void
    {
        $this->player->setName($name);
        $this->entityManager->persist($this->player);
        $this->entityManager->flush();
    }

    #[LiveAction]
    public function updateNumber(int $number): void
    {
        $this->player->setNumber($number);
        $this->entityManager->persist($this->player);
        $this->entityManager->flush();
    }

    public function getAvailablePositions(): array
    {
        if (!$this->factionId) {
            return [];
        }

        return $this->positionRepository->findBy(['faction' => $this->factionId]);
    }

    #[LiveAction]
    public function toggleEdit(): void
    {
        $this->isEditing = !$this->isEditing;
    }

    #[LiveAction]
    public function updatePlayer(#[LiveArg('model')] string $model, #[LiveArg('value')] mixed $value): void
    {
        match ($model) {
            'position' => $value ? $this->player->setPosition($this->positionRepository->find((int) $value)) : null,
            default => null,
        };

        $this->entityManager->persist($this->player);
        $this->entityManager->flush();
    }

    #[LiveAction]
    public function remove(): void
    {
        $this->entityManager->remove($this->player);
        $this->entityManager->flush();
    }

    #[LiveAction]
    public function add(int $teamId): void
    {
        $this->isNew = true;
        $this->isEditing = true;
        
        $player = new Player();
        $player->setTeam($this->entityManager->getReference(Team::class, $teamId));
        
        $this->player = $player;
    }
} 