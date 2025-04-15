<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\GameLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GameLog>
 *
 * @method GameLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameLog[]    findAll()
 * @method GameLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameLog::class);
    }

    /**
     * Récupère les N derniers logs d'un jeu donné
     */
    public function findLatestGameLogs(Game $game, int $limit = 10): array
    {
        return $this->createQueryBuilder('gl')
            ->andWhere('gl.game = :game')
            ->setParameter('game', $game)
            ->orderBy('gl.id DESC, gl.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
} 