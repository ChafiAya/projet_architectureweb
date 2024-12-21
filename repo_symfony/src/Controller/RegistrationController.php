<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        
        // Default role is 'ROLE_USER'. You can change it to 'ROLE_ADMIN' based on a condition.
        $user->setRoles('ROLE_USER'); // Set default role to 'ROLE_USER'
        
        // If you want to assign 'ROLE_ADMIN' based on some logic (e.g., checkbox or condition):
        // $user->setRoles('ROLE_ADMIN'); // Set to admin if needed

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            
            // Optionally hash the password (if needed):
            // $user->setPassword($passwordHasher->hashPassword($user, $plainPassword));
            $user->setPassword($plainPassword);  // In this example, plain password is set directly

            // Persist the user entity
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_enseignant_index'); // Redirect after successful registration
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
