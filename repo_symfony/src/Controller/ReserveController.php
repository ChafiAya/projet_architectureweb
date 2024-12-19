<?php

namespace App\Controller;

use App\Entity\Reserve;
use App\Entity\Sale;
use App\Entity\Promotion;
use App\Form\ReserveType;
use App\Repository\ReserveRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reserve')]
final class ReserveController extends AbstractController
{
    #[Route(name: 'app_reserve_index', methods: ['GET'])]
    public function index(ReserveRepository $reserveRepository): Response
    {
        return $this->render('reserve/index.html.twig', [
            'reserves' => $reserveRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reserve_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ReserveRepository $reserveRepository): Response
    {
        $reserve = new Reserve();
        $form = $this->createForm(ReserveType::class, $reserve);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Retrieve data from Reserve
            $selectedSalles = $reserve->getSalles();
            $selectedEnseignants = $reserve->getEnseignants();
            $selectDate_reservation = $reserve->getDateReservation();
            $selectHeureDebut = $reserve->getHeureDepart();
            $selectHeureFin = $reserve->getHeureFin();     
            $selectedPromotion = $reserve->getPromotion();  

            // Check for conflicts for each Enseignant
            foreach ($selectedEnseignants as $enseignant) {
                $conflitEnseignant = $reserveRepository->findConflictingReservationsForEnseignant(
                    $enseignant, $selectDate_reservation, $selectHeureDebut, $selectHeureFin
                );
                if (count($conflitEnseignant) > 0) {
                    $this->addFlash('error', 'Vous avez déjà une réservation durant cette période.');
                    return $this->render('reserve/new.html.twig', [
                        'reserve' => $reserve,
                        'form' => $form,
                    ]);
                }
            }

            // Check for classroom capacity conflicts
            foreach ($selectedSalles as $salle) {
                foreach ($selectedPromotion as $promo) {
                    $conflictCapacity = $reserveRepository->findConflictCapacityClassRoom($salle, $promo);
                    if (count($conflictCapacity) > 0) {
                        $this->addFlash('error', sprintf(
                            'La salle ne peut pas accueillir la promotion car la capacité maximale est insuffisante pour %d étudiants.',
                            $promo->getNbrEtudiant()

                        ));

                        return $this->render('reserve/new.html.twig', [
                            'reserve' => $reserve,
                            'form' => $form,
                        ]);
                    }
                }
            }

            // Check for room reservation conflicts
            foreach ($selectedSalles as $salle) {
                $conflitReservation = $reserveRepository->findConflictingReservations($salle, $selectDate_reservation, $selectHeureDebut, $selectHeureFin);
                if (count($conflitReservation) > 0) {
                    $this->addFlash('error', 'La salle sélectionnée est déjà réservée pendant cette période.');
                    return $this->render('reserve/new.html.twig', [
                        'reserve' => $reserve,
                        'form' => $form,
                    ]);
                }
            }

            // Save the reservation
            $entityManager->persist($reserve);
            $entityManager->flush();

            return $this->redirectToRoute('app_reserve_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reserve/new.html.twig', [
            'reserve' => $reserve,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reserve_show', methods: ['GET'])]
    public function show(Reserve $reserve): Response
    {
        return $this->render('reserve/show.html.twig', [
            'reserve' => $reserve,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reserve_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reserve $reserve, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReserveType::class, $reserve);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reserve_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reserve/edit.html.twig', [
            'reserve' => $reserve,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reserve_delete', methods: ['POST'])]
    public function delete(Request $request, Reserve $reserve, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reserve->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reserve);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reserve_index', [], Response::HTTP_SEE_OTHER);
    }
}
