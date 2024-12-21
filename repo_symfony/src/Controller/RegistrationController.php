<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        // Check if the form was submitted and is valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Get the plain password from the form
            $plainPassword = $form->get('plainPassword')->getData();

            // Hash the plain password using Symfony's UserPasswordHasherInterface
            $hashedPassword = $userPasswordHasher->hashPassword($user, $plainPassword);

            // Set the hashed password to the user entity
            $user->setPassword($hashedPassword);

            // Get the selected role from the form (this now works with 'roles')
            $roles = $form->get('roles')->getData(); // Get the selected role

            // Set the selected role to the user
            $user->setRoles($roles); // Set the role(s)

            // Persist the user entity
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirect after successful registration
            return $this->redirectToRoute('app_enseignant_index');
        }

        // Render the registration form
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
