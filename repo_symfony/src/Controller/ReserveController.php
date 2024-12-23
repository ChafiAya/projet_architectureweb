<?php

namespace App\Controller;

use App\Entity\Reserve;
use App\Entity\Sale;
use App\Form\ReserveType;
use App\Repository\ReserveRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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

            //recuperer data de la Reserve
            $selectedSalles = $reserve->getSalles();
            $selectedEnseignants = $reserve->getEnseignants();
            $selectDate_reservation = $reserve->getDateReservation();
            $selectHeureDebut = $reserve->getHeureDepart();
            $selectHeureFin = $reserve->getHeureFin();     
            $selectedPromotion = $reserve->getPromotion();  
            

            
            foreach($selectedEnseignants as $enseignant){
                $conflitEnseignant = $reserveRepository->findConflictingReservationsForEnseignant(
                    $enseignant, $selectDate_reservation, $selectHeureDebut, $selectHeureFin
                );
                if(count($conflitEnseignant)>0){
                    $this->addFlash('error',sprintf('Vous avez  déjà une réservation durant cette période.'));
                    return $this->render('reserve/new.html.twig', [
                        'reserve' => $reserve,
                        'form' => $form,
                    ]);
                }
            }

            //verifier le conflit de capasiter de salle avec le nombre d'etudaint 
            foreach($selectedSalles as $salles){
                foreach($selectedPromotion as $promo){
                    $fincConflicatCapacity = $reserveRepository->findConflictCapacityClassRoom($salles,$promo);
                    if(count($fincConflicatCapacity)>0){
                        $this->addFlash('error', sprintf(
                            'La salle ne peut pas accueillir la promotion car la capacité maximale est insuffisante pour %d étudiants.',

                            )
                        );

                        return $this->render('reserve/new.html.twig',[
                            'reserve' => $reserve,
                            'form' => $form,
                        ]);
                    }
                }
            }

            //verification de conflit 
            foreach($selectedSalles as $sale ){
                $conflitReservation = $reserveRepository->findConflictingReservations($sale,$selectDate_reservation,$selectHeureDebut,$selectHeureFin);
                if(count($conflitReservation)>0){
                    $this->addFlash('error', 'The selected room is already reserved during the specified time.');
                    return $this->render('reserve/new.html.twig',[      
                        'conflitReservation'=> $conflitReservation,         
                        'reserve' => $reserve,
                        'form' => $form,
                ]);
                }
            }

            foreach($selectedSalles as $salle){
                $salle->setDisponibilite(false);
                $entityManager->persist($salle);
            }


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
        if ($this->isCsrfTokenValid('delete'.$reserve->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($reserve);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reserve_index', [], Response::HTTP_SEE_OTHER);
    }
}
