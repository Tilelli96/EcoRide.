<?php

namespace App\Repository;

use App\Entity\Avis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Avis>
 */
class AvisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Avis::class);
    }

    /**
     * @return Avis[] Returns an array of Avis objects
     */
    public function findByUser($user): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.user = :id')
            ->andWhere('a.statut = :statut')
            ->setParameter('id', $user)
            ->setParameter('statut', 'validé')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByStatut(): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.statut = :statut')
            ->setParameter('statut', 'à confirmer')
            ->getQuery()
            ->getResult()
        ;
    }

    //    public function findOneBySomeField($value): ?A
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}