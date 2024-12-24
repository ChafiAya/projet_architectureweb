<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/agent', name: 'agent_')]
class AgentController extends AbstractController
{
    #[Route('/planning', name: 'planning')]
    public function planning(): Response
    {
        // Ajoutez la logique pour afficher le planning global
        return $this->render('agent/planning.html.twig', [
            'controller_name' => 'AgentController',
        ]);
    }
}
?>