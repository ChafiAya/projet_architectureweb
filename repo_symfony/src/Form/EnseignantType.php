<?php

namespace App\Form;

use App\Entity\Enseignant;
use App\Entity\Matiere;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class EnseignantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('Prenom', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('Email', TextType::class, [
                'label' => 'Email (e.g., prenom.nom.etu@univ-lille.fr)',
                'attr' => [
                    'placeholder' => 'prenom.nom.etu@univ-lille.fr',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-zA-Z]+(?:\.[a-zA-Z]+)*\.etu@univ-lille\.fr$/',
                        'message' => 'Veuillez saisir un email valide au format prenom.nom.etu@univ-lille.fr.',
                    ]),
                ],
            ])
            ->add('id_matiere', EntityType::class, [
                'class' => Matiere::class,
                'choice_label' => 'nom',
                'label' => 'Matière',
                'placeholder' => 'Sélectionnez une matière',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Enseignant::class,
        ]);
    }
}
