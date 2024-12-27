<?php

namespace App\Controller;
use App\Entity\Reserve;
use App\Entity\Sale;
use App\Entity\Promotion;
use App\Form\ReserveType;
use App\Repository\ReserveRepository;
use App\Repository\SaleRepository;
use App\Repository\PromotionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reserve')]
final class ReserveController extends AbstractController
{

    #[Route('/', name: 'app_reserve_index', methods: ['GET'])]
    public function index(Request $request, ReserveRepository $reserveRepository, SaleRepository $saleRepository, PromotionRepository $promotionRepository): Response
    {
        //recuperer l'utilisateur connecter
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        // dd($user, $user->getEnseignant());
        $reserves = [];

        $salleId = $request->query->get('salle_id');
        $dateReservation = $request->query->get('date_reservation');
        $promotionId = $request->query->get('promotion_id');

        // Vérification du rôle
        if ($this->isGranted('ROLE_ADMIN')) {
            // Admin peut voir toutes les réservations avec filtres
            $salleId = $salleId ? (int) $salleId : null;
            $promotionId = $promotionId ? (int) $promotionId : null;
            // Appeler la méthode existante pour filtrer
            $reserves = $reserveRepository->findByFilters($salleId, $dateReservation, $promotionId);
        } 
        elseif ($this->isGranted('ROLE_ENSEIGNANT')) {
            
            // Enseignant peut voir uniquement ses propres réservations
            $enseignant = $user->getEnseignant(); 

            if ($enseignant) {
                $reserves = $reserveRepository->SelectEnseignant($enseignant);
            }
            else{
                $this->addFlash('error', 'Aucun enseignant trouvé pour cet utilisateur.');
            }
        }

        // Récupération des salles et promotions pour l'affichage
        $salles = $saleRepository->findAll();
        $promotions = $promotionRepository->findAll();
    
        // Render the template with reserves, salles, promotions, and selected filters
        return $this->render('reserve/index.html.twig', [
            'reserves' => $reserves,
            'salles' => $salles,
            'promotions' => $promotions,
            'salleId' => $salleId, // Pass the selected salle ID to highlight in dropdown
            'dateReservation' => $dateReservation, // Pass the selected date to highlight in input field
            'promotionId' => $promotionId, // Pass the selected promotion ID
        ]);
    }
    

    #[Route('/new', name: 'app_reserve_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ReserveRepository $reserveRepository): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if (!$user || !($this->isGranted('ROLE_ENSEIGNANT') || $this->isGranted('ROLE_ADMIN'))) {
            throw $this->createAccessDeniedException();
        }
        
        $reserve = new Reserve();
        $formOptions = ['exclude_enseignants' => false];        

        // Create form but exclude enseignants field if user is ROLE_ENSEIGNANT
        $formOptions = [];
        if ($this->isGranted('ROLE_ENSEIGNANT')) {
            $formOptions['excl ude_enseignants'] = true;
        }
 
        //verificarion is le l'utilisateur a le role d'un enseignant
        if ($this->isGranted( 'ROLE_ENSEIGNANT')) {
            $enseignant = $user->getEnseignant();
            if ($enseignant) {         
                $reserve->addEnseignant($enseignant);
            }
        }

        $form = $this->createForm(ReserveType::class, $reserve,[
            'exclude_enseignants' => $this->isGranted('ROLE_ENSEIGNANT'),
        ]);
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
                if(count($conflitEnseignant)>0){
                    $this->addFlash('error',sprintf('Vous avez  déjà une réservation durant cette période.'));
                    $this->redirectToRoute('app_reserve_new');
                }
            }

            // Check for classroom capacity conflicts
            foreach ($selectedSalles as $salle) {
                foreach ($selectedPromotion as $promo) {
                    $conflictCapacity = $reserveRepository->findConflictCapacityClassRoom($salle, $promo);
                    if (count($conflictCapacity) > 0) {
                        $this->addFlash('error', sprintf(
                            'La salle ne peut pas accueillir la promotion car la capacité maximale est insuffisante pour %d étudiants.',

                        ));

                        $this->redirectToRoute('app_reserve_new');
                    }
                }
            }

            //verification de conflit 
            foreach($selectedSalles as $sale ){
                $conflitReservation = $reserveRepository->findConflictingReservations($sale,$selectDate_reservation,$selectHeureDebut,$selectHeureFin);
                if(count($conflitReservation)>0){
                    $this->addFlash('error', 'The selected room is already reserved during the specified time.');
                    return $this->redirectToRoute('app_reserve_new');
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

