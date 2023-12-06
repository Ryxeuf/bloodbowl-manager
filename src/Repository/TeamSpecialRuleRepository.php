<?php

namespace App\Repository;

use App\Entity\TeamSpecialRule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TeamSpecialRule>
 *
 * @method TeamSpecialRule|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeamSpecialRule|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeamSpecialRule[]    findAll()
 * @method TeamSpecialRule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamSpecialRuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeamSpecialRule::class);
    }

//    /**
//     * @return TeamSpecialRule[] Returns an array of TeamSpecialRule objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TeamSpecialRule
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
