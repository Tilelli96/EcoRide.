<?php

namespace App\Repository;

use App\Entity\Covoiturage;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Covoiturage>
 */
class CovoiturageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Covoiturage::class);
    }

    /**
        * @return Covoiturage[] Returns an array of Covoiturage objects
    */     
    public function findBySearch(array $search){
        return $this->createQueryBuilder('c')
                ->where('c.lieu_arrivee = :arrivee')
                ->andWhere('c.lieu_depart = :Ldepart')
                ->andWhere('c.date_depart = :Ddepart')
                ->andWhere('c.statut = :Statut')
                ->setParameter('Ldepart', $search['adresse_depart'])
                ->setParameter('Ddepart', $search['Date'])
                ->setParameter('arrivee', $search['adresse_arrivee'])
                ->setParameter('Statut', 'à venir')
                ->getQuery()
                ->getResult();
    }

    public function findByOtherDate(array $search){
        return $this->createQueryBuilder('c')
                ->where('c.lieu_arrivee = :arrivee')
                ->andWhere('c.lieu_depart = :Ldepart')
                ->andWhere('c.statut = :Statut')
                ->setParameter('Ldepart', $search['adresse_depart'])
                ->setParameter('arrivee', $search['adresse_arrivee'])
                ->setParameter('Statut', 'à venir')
                ->getQuery()
                ->getResult();
    }

    public function findByHistoricalUser(User $user){
        return $this->createQueryBuilder('c')
                    ->andWhere('c.user = :user')
                    ->setParameter('user', $user)
                    ->orderBy('c.date_depart', 'ASC')
                    ->getQuery()
                    ->getResult();
    }

    public function findByPassager(User $user)
    {
        return $this->createQueryBuilder('c')
            ->join('c.voyageurs', 'v') 
            ->where('v.id = :userId') 
            ->setParameter('userId', $user->getId())
            ->orderBy('c.date_depart', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function countCovoituragesPerDay(): array
    {
        return $this->createQueryBuilder('c')
            ->select("YEAR(c.date_depart) as annee, MONTH(c.date_depart) as mois, DAY(c.date_depart) as jour, COUNT(c.id) as nombre_covoiturages")
            ->groupBy('annee, mois, jour')
            ->orderBy('annee, mois, jour', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getGainsParJour()
    {
        return $this->createQueryBuilder('c')
            ->select('c.date_depart AS jour, COUNT(voyageurs.id) * 2 AS credits_gagnes')
            ->leftJoin('c.voyageurs', 'voyageurs')
            ->groupBy('jour')
            ->orderBy('jour', 'DESC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Covoiturage[] Returns an array of Covoiturage objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Covoiturage
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}