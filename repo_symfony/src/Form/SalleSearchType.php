<?php
namespace App\Form;

use App\Entity\Sale;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SalleSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // This form will only have a select dropdown for choosing a room (Salle)
        $builder
            ->add('salle', ChoiceType::class, [
                'choices' => $options['salles'], // List of available salles
                'choice_label' => function(Sale $salle) {
                    return $salle->getNomDeSalle();
                },
                'placeholder' => 'Select a Room',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'salles' => [], // Default empty salles
        ]);
    }
}
