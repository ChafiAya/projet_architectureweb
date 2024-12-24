<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        // Return a more visually appealing HTML response
        return new Response(
            '<html>
                <head>
                    <title>Welcome to My Homepage</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f8f9fa;
                            margin: 0;
                            padding: 20px;
                        }
                        h1 {
                            color: #343a40;
                            text-align: center;
                        }
                        .container {
                            max-width: 800px;
                            margin: 0 auto;
                            padding: 20px;
                            background-color: #ffffff;
                            border-radius: 8px;
                            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                        }
                        ul {
                            list-style-type: none;
                            padding: 0;
                        }
                        li {
                            margin: 15px 0;
                        }
                        a {
                            text-decoration: none;
                            color: #007bff;
                            font-weight: bold;
                        }
                        a:hover {
                            text-decoration: underline;
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <h1>Welcome to My Homepage!</h1>
                        <p>This is a simple homepage with links to other pages in the application.</p>
                        <ul>
                            <li><a href="/enseignant">enseignant</a></li>
                            <li><a href="/matiere">matiere</a></li>
                            <li><a href="/promotion">promotion</a></li>
                            <li><a href="/sale">salle</li>
                            <li><a href="/reserve">reservation</a></li>
                            <li><a href="/register">registration of student or teacher</a></li>
                           
                        </ul>
                    </div>
                </body>
            </html>'
        );
    }
}