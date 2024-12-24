<?php
namespace App\Controller;

use App\Entity\User;
use App\Entity\Promotion;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);

        // Retrieve promotions based on the selected role (only for "Etudiant")
        if ($user->getRoles() === 'Etudiant') {
            // Retrieve promotions and format them as "niveau_promotion + enseignement"
            $promotions = $entityManager->getRepository(Promotion::class)->findAll();
            $promotionChoices = [];

            foreach ($promotions as $promotion) {
                $promotionChoices[$promotion->getNiveauPromotion() . ' - ' . $promotion->getEnseignement()] = $promotion->getId();
            }

            // Add the dynamic promotion choices to the form
            $form->add('promotion', ChoiceType::class, [
                'label' => 'Select your promotion',
                'choices' => $promotionChoices,
                'mapped' => false, // We do not map it to the user entity directly
                'expanded' => false,
                'multiple' => false,
            ]);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hash the password
            $plainPassword = $form->get('plainPassword')->getData();
            $hashedPassword = $userPasswordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);

            // Get the selected role and set it
            $roles = $form->get('roles')->getData();
            $user->setRoles($roles);

            // Handle the selected promotion for Etudiant
            if ($roles === 'Etudiant') {
                $promotionId = $form->get('promotion')->getData();
                $promotion = $entityManager->getRepository(Promotion::class)->find($promotionId);
                $user->setPromotion($promotion);
            }

            // Persist the user entity
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirect after successful registration
            return $this->redirectToRoute('app_reserve_index');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}

