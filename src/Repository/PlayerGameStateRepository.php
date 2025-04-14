<?php

namespace App\Repository;

use App\Entity\PlayerGameState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PlayerGameState>
 *
 * @method PlayerGameState|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlayerGameState|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlayerGameState[]    findAll()
 * @method PlayerGameState[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerGameStateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlayerGameState::class);
    }

    public function save(PlayerGameState $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PlayerGameState $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
} 