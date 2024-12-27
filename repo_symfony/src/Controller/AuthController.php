<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AuthController extends AbstractController
{
    #[Route('/', name: 'app_auth')]
    public function index(): Response
    {
        // Return a more visually appealing HTML response

        return $this->render('auth/index.html.twig');

    }
}
