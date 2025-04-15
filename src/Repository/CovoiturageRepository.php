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
                ->setParameter('Ldepart', $search['search']['adresse_depart'])
                ->setParameter('Ddepart', $search['search']['Date'])
                ->setParameter('arrivee', $search['search']['adresse_arrivee'])
                ->setParameter('Statut', 'à venir')
                ->getQuery()
                ->getResult();
    }

    public function findByOtherDate(array $search){
        return $this->createQueryBuilder('c')
                ->where('c.lieu_arrivee = :arrivee')
                ->andWhere('c.lieu_depart = :Ldepart')
                ->andWhere('c.statut = :Statut')
                ->setParameter('Ldepart', $search['search']['adresse_depart'])
                ->setParameter('arrivee', $search['search']['adresse_arrivee'])
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

    /**
     * Récupère le nombre de covoiturages par jour.
     *
     * @return array
     */
    public function countCovoituragesPerDay(): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "
            SELECT DATE(c.date_depart) AS jour, COUNT(c.id) AS nombre_covoiturages
            FROM covoiturage c
            WHERE c.statut = 'passé'
            GROUP BY jour
            ORDER BY jour ASC
            LIMIT 10;
        ";
        $stmt = $conn->executeQuery($sql);
        $results = $stmt->fetchAllAssociative();
        return $results;
    }

    /**
     * Récupère le nombre de gains par jour.
     *
     * @return array
     */
    public function getGainsParJour()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "
            SELECT 
                DATE_FORMAT(c.date_depart, '%Y-%m-%d') AS jour,
                COUNT(voyageurs.id) * 2 AS credits_gagnes
            FROM covoiturage c
            LEFT JOIN covoiturage_voyageurs cv ON c.id = cv.covoiturage_id
            LEFT JOIN user voyageurs ON cv.user_id = voyageurs.id
            WHERE c.statut = 'passé'
            GROUP BY jour
            ORDER BY jour DESC
            LIMIT 10;
        ";
        $stmt = $conn->executeQuery($sql);
        $results = $stmt->fetchAllAssociative();
        return $results;
    }

    public function getTotal(){
        return (int) $this->createQueryBuilder('c')
            ->select('COUNT(voyageurs.id) * 2')
            ->leftJoin('c.voyageurs', 'voyageurs')
            ->where('c.statut = :statut')
            ->setParameter('statut', 'passé')
            ->getQuery()
            ->getSingleScalarResult();
    }
}