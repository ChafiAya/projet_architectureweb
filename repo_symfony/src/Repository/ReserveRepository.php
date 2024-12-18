<?php

namespace App\Repository;

use App\Entity\Enseignant;
use App\Entity\Promotion;
use App\Entity\Reserve;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Sale;

/**
 * @extends ServiceEntityRepository<Reserve>
 */
class ReserveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reserve::class);
    }


    public function findConflictingReservationsForEnseignant(Enseignant $enseignant, \DateTimeInterface $date, \DateTimeInterface $startTime, \DateTimeInterface $endTime ):array{
        return $this->createQueryBuilder('r')
        ->innerJoin('r.enseignants','e')
        ->andWhere('e.id= :enseignantId')
        ->andWhere('r.date_reservation =:date')
        ->andWhere('((r.heure_depart < :endTime AND r.heure_fin > :startTime))')
        ->setParameter("enseignantId",$enseignant->getId())
        ->setParameter("date",$date->format('Y-m-d'))
        ->setParameter("endTime",$endTime->format('H:i:s'))
        ->setParameter('startTime', $startTime->format('H:i:s'))
        ->getQuery()
        ->getResult();
    }   
    public function findConflictCapacityClassRoom(Sale $salle, Promotion $promotion): array
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.salles', 's')  // Jointure avec les salles
            ->innerJoin('r.promotion', 'p') // Jointure avec les promotions
            ->andWhere('s.id = :salleId')  // Filtre sur la salle
            ->andWhere('p.id = :promotionId') // Filtre sur la promotion
            ->andWhere('p.nbr_etudiants > s.capacite') // Vérification de la capacité
            ->setParameter('salleId', $salle->getId())
            ->setParameter('promotionId', $promotion->getId())
            ->getQuery()
            ->getResult();
    }
    
    public function findConflictingReservations(Sale $salle, \DateTimeInterface $date, \DateTimeInterface $startTime, \DateTimeInterface $endTime): array{
        return $this->createQueryBuilder('r')
        ->innerJoin('r.salles','s')
        ->andWhere('s.id = :salleId')
        ->andWhere('r.date_reservation = :date')
        ->andWhere("((r.heure_depart < :endTime AND r.heure_fin > :startTime))")
        ->setParameter("salleId",$salle->getId())
        ->setParameter('date', $date->format('Y-m-d'))
        ->setParameter('startTime', $startTime->format('H:i:s'))
        ->setParameter('endTime', $endTime->format('H:i:s'))
        ->getQuery()
        ->getResult();
    }

//    /**
//     * @return Reserve[] Returns an array of Reserve objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reserve
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
