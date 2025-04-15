<?php

namespace App\Service;

use App\Entity\Game;
use App\Entity\GameLog;
use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;

class GameLogService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * Ajoute un log au jeu
     */
    public function addLog(Game $game, string $message, ?Player $player = null, ?string $type = null): GameLog
    {
        $log = new GameLog();
        $log->setGame($game)
            ->setMessage($message)
            ->setPlayer($player)
            ->setType($type);
        
        $this->entityManager->persist($log);
        $this->entityManager->flush();
        
        return $log;
    }

    /**
     * Récupère les derniers logs d'un jeu
     */
    public function getLatestLogs(Game $game, int $limit = 10): array
    {
        return $this->entityManager->getRepository(GameLog::class)
            ->findLatestGameLogs($game, $limit);
    }
} 