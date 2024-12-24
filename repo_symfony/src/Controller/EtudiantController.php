<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/etudiant', name: 'etudiant_')]
class EtudiantController extends AbstractController
{
    #[Route('/planning', name: 'planning')]
    public function planning(): Response
    {
        // Ajoutez la logique pour afficher le planning de l'étudiant connecté
        return $this->render('etudiant/planning.html.twig', [
            'controller_name' => 'EtudiantController',
        ]);
    }
}
?>