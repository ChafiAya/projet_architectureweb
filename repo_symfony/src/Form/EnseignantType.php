<?php

namespace App\Form;

use App\Entity\Enseignant;
use App\Entity\Matiere;
use App\Entity\Reserve;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnseignantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_enseignant')
            ->add('prenom')
            ->add('email_enseignant')
            ->add('departement')
            // ->add('matieres', EntityType::class, [
            //     'class' => Matiere::class,
            //     'choice_label' => 'id',
            //     'multiple' => true,
            // ])
            // ->add('reserves', EntityType::class, [
            //     'class' => Reserve::class,
            //     'choice_label' => 'id',
            //     'multiple' => true,
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Enseignant::class,
        ]);
    }
}
