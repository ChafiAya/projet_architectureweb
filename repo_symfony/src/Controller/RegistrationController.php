<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Enseignant;
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

        // Création du formulaire d'inscription avec l'entité User
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        // Vérifie si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération et hashage du mot de passe
            $plainPassword = $form->get('plainPassword')->getData();
            $hashedPassword = $userPasswordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);

            // Récupération du type d'utilisateur sélectionné (typeUtilisateur)
            $typeUtilisateur = $form->get('typeUtilisateur')->getData();

            // Attribution des rôles selon le type d'utilisateur
            switch ($typeUtilisateur) {
                case 'ROLE_ETUDIANT':
                    $user->setRoles(['ROLE_ETUDIANT']);
                    break;

                case 'ROLE_ENSEIGNANT':
                    $user->setRoles(['ROLE_ENSEIGNANT']);

                    // Création de l'entité Enseignant si le rôle est Enseignant
                    $enseignant = new Enseignant();
                    $enseignant->setNomEnseignant($form->get('nom')->getData());
                    $enseignant->setPrenom($form->get('prenom')->getData());
                    $enseignant->setEmailEnseignant($user->getEmail());

                    // Enregistre l'entité Enseignant
                    $entityManager->persist($enseignant);

                    // Lie l'utilisateur à l'entité Enseignant
                    $user->setEnseignant($enseignant);
                    break;

                case 'ROLE_AGENT':
                    $user->setRoles(['ROLE_AGENT']);
                    break;

                default:
                    throw new \LogicException('Type d\'utilisateur inconnu.');
            }

            // Persiste l'utilisateur dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirection après une inscription réussie
            return $this->redirectToRoute('app_reserve_index');
        }

        // Rend le formulaire d'inscription
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
