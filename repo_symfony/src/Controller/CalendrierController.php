<?php

namespace App\Controller;

use App\Repository\ReserveRepository;
use App\Repository\SaleRepository;
use App\Repository\PromotionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendrierController extends AbstractController
{
    #[Route('/calendrier', name: 'app_calendrier', methods: ['GET'])]
    public function index(
        Request $request,
        ReserveRepository $reserveRepository,
        SaleRepository $saleRepository,
        PromotionRepository $promotionRepository
    ): Response {
        //Recuperer le id salle
        $salleId = $request->query->get('salle_id');
        //recuperer id promotion
        $promotionId = $request->query->get('promotion_id');

        // Convertir les paramètres en entier ou null
        $salleId = $salleId ? (int) $salleId : null;
        $promotionId = $promotionId ? (int) $promotionId : null;

        // Filtrer les réservations
        $reservations = $reserveRepository->findByFilters($salleId, null, $promotionId);

        // Récupérer toutes les salles et promotions pour les listes déroulantes
        $salles = $saleRepository->findAll();
        $promotions = $promotionRepository->findAll();

        // Renvoyer les données au template
        return $this->render('calendrier/index.html.twig', [
            'reservations' => $reservations,
            'salles' => $salles,
            'promotions' => $promotions,
            'salleId' => $salleId, // Salle sélectionnée
            'promotionId' => $promotionId, // Promotion sélectionnée
        ]);
    }
}
