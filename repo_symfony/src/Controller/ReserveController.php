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
use App\Message\UpdateDisponibiliteMessage;
use Symfony\Component\Messenger\MessageBusInterface;

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
    public function new(Request $request, EntityManagerInterface $entityManager, ReserveRepository $reserveRepository,MessageBusInterface $bus): Response
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
                    return $this->redirectToRoute('app_reserve_new');
                }
            }

            //verifier le conflit de capasiter de salle avec le nombre d'etudaint 
            foreach($selectedSalles as $salles){
                foreach($selectedPromotion as $promo){
                    $fincConflicatCapacity = $reserveRepository->findConflictCapacityClassRoom($salles,$promo);
                    if(count($fincConflicatCapacity)>0){
                        $this->addFlash('error', sprintf(
                            'La salle ne peut pas accueillir la promotion car la capacité maximale est insuffisante pour les étudiants.',

                            )
                        );

                        return $this->redirectToRoute('app_reserve_new');
                    }
                }
            }

            //verification de conflit 
            foreach($selectedSalles as $sale ){
                $conflitReservation = $reserveRepository->findConflictingReservations($sale,$selectDate_reservation,$selectHeureDebut,$selectHeureFin);
                if(count($conflitReservation)>0){
                    $this->addFlash('error', 'La salle est deja selectionner par un autre enseignant. Veillez prendre un autre creneau SVP!.');
                    
                    return $this->redirectToRoute('app_reserve_new');
                }
            }

            foreach($selectedSalles as $salle){
                $salle->updateDisponibilite();
                $entityManager->persist($salle);
            }

            //planification de la mis a jour apres la fin de la reservation 
            //parcour des salles
            foreach ($selectedSalles as $salle) {
                //heur fin 
                $endTime = clone $reserve->getHeureFin();
                $dateReservation = $reserve->getDateReservation();

                if (!$endTime || !$dateReservation) {
                    throw new \InvalidArgumentException('Heure de fin ou date de réservation invalide.');
                }
                if (!$endTime instanceof \DateTime) {
                    $endTime = new \DateTime($endTime->format('Y-m-d H:i:s'));
                }

                $endTime->setDate(
                    $reserve->getDateReservation()->format('Y'),
                    $reserve->getDateReservation()->format('m'),
                    $reserve->getDateReservation()->format('d')
                );
                //dispache de message pour mettre a jour la disponibilite de salle
                $bus->dispatch(new UpdateDisponibiliteMessage($salle->getId()));
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
