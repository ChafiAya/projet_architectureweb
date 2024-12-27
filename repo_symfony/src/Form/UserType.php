<?php

namespace App\Form;

use App\Entity\Enseignant;
use App\Entity\Promotion;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Regex;  

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null, [
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-zA-Z]+(?:\.[a-zA-Z]+)*\.etu@univ-lille\.fr$/', 
                        'message' => 'Please enter a valid email in the format prenom.nom.etu@univ-lille.fr',
                    ]),
                ],
                'label' => 'Email (e.g., prenom.nom.etu@univ-lille.fr)',
            ])
            ->add('password')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'Etudiant' => 'ROLE_ETUDIANT',
                    'Enseignant' => 'ROLE_ENSEIGNANT',
                ],
                'multiple' => false,  
                'expanded' => true, 
                'attr' => ['class' => 'form-check-inline'],
                'data' => 'ROLE_USER',
            ])
            
            ->add('enseignant', EntityType::class, [
                'class' => Enseignant::class,
                'choice_label' => 'id',
                'attr' => ['class' => 'form-select'],
                'placeholder' => 'None',
                'required' => false,
            ])
            ->add('promotion', EntityType::class, [
                'class' => Promotion::class,
                'choice_label' => 'id',
                'attr' => ['class' => 'form-select'],
                'placeholder' => 'None', 
                'required' => false, 
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
