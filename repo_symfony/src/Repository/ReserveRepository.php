<?php

namespace App\Repository;
use App\Entity\User;
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

    /**
     * Find conflicting reservations for an enseignant on a specific date and time.
     *
     * @param Enseignant $enseignant
     * @param \DateTimeInterface $date
     * @param \DateTimeInterface $startTime
     * @param \DateTimeInterface $endTime
     * @return array
     */
    public function findConflictingReservationsForEnseignant(Enseignant $enseignant, \DateTimeInterface $date, \DateTimeInterface $startTime, \DateTimeInterface $endTime): array
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.enseignants', 'e')
            ->andWhere('e.id = :enseignantId')
            ->andWhere('r.date_reservation = :date')
            ->andWhere('r.heure_depart < :endTime')
            ->andWhere('r.heure_fin > :startTime')
            ->setParameter('enseignantId', $enseignant->getId())
            ->setParameter('date', $date->format('Y-m-d')) // Assuming date is stored as 'Y-m-d'
            ->setParameter('startTime', $startTime->format('H:i:s'))
            ->setParameter('endTime', $endTime->format('H:i:s'))
            ->getQuery()
            ->getResult();
    }

    /**
     * Find conflicting reservations for a specific room and time slot.
     *
     * @param Sale $salle
     * @param \DateTimeInterface $date
     * @param \DateTimeInterface $startTime
     * @param \DateTimeInterface $endTime
     * @return array
     */
    public function findConflictingReservations(Sale $salle, \DateTimeInterface $date, \DateTimeInterface $startTime, \DateTimeInterface $endTime): array
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.salles', 's')
            ->andWhere('s.id = :salleId')
            ->andWhere('r.date_reservation = :date')
            ->andWhere('r.heure_depart < :endTime')
            ->andWhere('r.heure_fin > :startTime')
            ->setParameter('salleId', $salle->getId())
            ->setParameter('date', $date->format('Y-m-d')) // Assuming the reservation is stored as date
            ->setParameter('startTime', $startTime->format('H:i:s'))
            ->setParameter('endTime', $endTime->format('H:i:s'))
            ->getQuery()
            ->getResult();
    }

    /**
     * Find conflicts with the room capacity and number of students in the promotion.
     *
     * @param Sale $salle
     * @param Promotion $promotion
     * @return array
     */
    public function findConflictCapacityClassRoom(Sale $salle, Promotion $promotion): array
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.salles', 's')
            ->innerJoin('r.promotion', 'p')
            ->andWhere('s.id = :salleId')
            ->andWhere('p.id = :promotionId')
            ->andWhere('p.nbr_etudiants > s.capacite') // Check if students exceed room capacity
            ->setParameter('salleId', $salle->getId())
            ->setParameter('promotionId', $promotion->getId())
            ->getQuery()
            ->getResult();
    }

    public function findByFilters(?int $salleId, ?string $dateReservation, ?int $promotionId): array
    {
        $qb = $this->createQueryBuilder('r')
            ->innerJoin('r.salles', 's')
            ->leftJoin('r.promotion', 'p');
    
        if ($salleId) {
            $qb->andWhere('s.id = :salleId')
                ->setParameter('salleId', $salleId);
        }
    
        if ($dateReservation) {
            $qb->andWhere('r.date_reservation = :dateReservation')
                ->setParameter('dateReservation', $dateReservation);
        }
    
        if ($promotionId) {
            $qb->andWhere('p.id = :promotionId')
                ->setParameter('promotionId', $promotionId);
        }
    
        return $qb->getQuery()->getResult();
    }
    //cette methode nous permet de selectionner les reservation effectuer par un enseignant specifique
    public function SelectEnseignant(Enseignant $enseignant): array
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.enseignants', 'e')
            ->where('e = :enseignant')
            ->setParameter('enseignant', $enseignant)
            ->getQuery()
            ->getResult();
    }
    public function findByEnseignant(User $enseignant)
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.enseignants', 'e')
            ->where('e.id = :enseignantId')
            ->setParameter('enseignantId', $enseignant->getId())
            ->getQuery()
            ->getResult();
    }  

}
