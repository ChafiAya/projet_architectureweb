<?php

namespace App\Form;

use App\Entity\Enseignant;
use App\Entity\Reserve;
use App\Entity\Sale;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReserveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_reservation', null, [
                'widget' => 'single_text',
            ])
            ->add('heure_depart', null, [
                'widget' => 'single_text',
            ])
            ->add('heure_fin', null, [
                'widget' => 'single_text',
            ])
            ->add('etat_reservation')
            ->add('salles', EntityType::class, [
                'class' => Sale::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('enseignants', EntityType::class, [
                'class' => Enseignant::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reserve::class,
        ]);
    }
}
