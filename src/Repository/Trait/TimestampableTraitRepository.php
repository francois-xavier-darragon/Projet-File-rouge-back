<?php

namespace App\Repository\Trait;

use App\Entity\Trait\TimestampableTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TimestampableTrait>
 *
 * @method TimestampableTrait|null find($id, $lockMode = null, $lockVersion = null)
 * @method TimestampableTrait|null findOneBy(array $criteria, array $orderBy = null)
 * @method TimestampableTrait[]    findAll()
 * @method TimestampableTrait[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimestampableTraitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TimestampableTrait::class);
    }

//    /**
//     * @return TimestampableTrait[] Returns an array of TimestampableTrait objects
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

//    public function findOneBySomeField($value): ?TimestampableTrait
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
