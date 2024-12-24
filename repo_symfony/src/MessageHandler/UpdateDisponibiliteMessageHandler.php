<?php

namespace App\MessageHandler;

use App\Message\UpdateDisponibiliteMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\Repository\SaleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use App\Repository\ReserveRepository;

#[AsMessageHandler]
final class UpdateDisponibiliteMessageHandler
{
    private SaleRepository $saleRepository;

    private EntityManagerInterface $entityManager;

    private LoggerInterface $logger;
    private ReserveRepository $reserveRepository;

    public function __construct(SaleRepository $saleRepository, EntityManagerInterface $entityManager,LoggerInterface $logger,ReserveRepository $reserveRepository)
    {
        $this->saleRepository = $saleRepository;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->reserveRepository=$reserveRepository;
    }
    public function __invoke(UpdateDisponibiliteMessage $message): void
    {
        $salle = $this->saleRepository->find($message->getSalleId());
        if (!$salle) {
            $this->logger->error('Salle non trouvée pour ID : ' . $message->getSalleId());
            return;
        }
    
        $currentDateTime = new \DateTime();
    
        // Vérifiez si une réservation existe encore pour cet instant
        $reservationsActuelles = $this->reserveRepository->findConflictingReservations(
            $salle,
            $currentDateTime,
            new \DateTime('00:00:00'), 
            new \DateTime('23:59:59')
        );
    
        if (empty($reservationsActuelles)) {
            $salle->setDisponibilite(true);
        } else {
            $salle->setDisponibilite(false);
        }
    
        $this->entityManager->persist($salle);
        $this->entityManager->flush();
    
        $this->logger->info('Disponibilité mise à jour pour la salle : ' . $salle->getNomDeSalle());
    }
    
}
