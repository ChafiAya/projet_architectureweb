<?php
namespace App\Form;

use App\Entity\User;
use App\Repository\PromotionRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    private PromotionRepository $promotionRepository;

    public function __construct(PromotionRepository $promotionRepository)
    {
        $this->promotionRepository = $promotionRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
                'label' => false,
                'attr' => [
                    'autocomplete' => 'email',
                    'class' => 'bg-transparent block mt-10 mx-auto border-b-2 w-1/5 h-20 text-2xl outline-none',
                    'placeholder' => 'Email'
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => false,
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'bg-transparent block mt-10 mx-auto border-b-2 w-1/5 h-20 text-2xl outline-none',
                    'placeholder' => 'Mot de passe'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Sélectionnez votre rôle',
                'choices' => [
                    'Enseignant' => 'ROLE_ENSEIGNANT',
                    'Etudiant' => 'ROLE_ETUDIANT',
                ],
                'expanded' => true,
                'multiple' => false,
                'data' => 'ROLE_USER',
            ])
            ->add('promotion', ChoiceType::class, [
                'label' => 'Sélectionnez votre promotion',
                'mapped' => false,
                'choices' => $this->getPromotionChoices(),
                'expanded' => false,
                'multiple' => false,
                // 'placeholder' => 'Sélectionnez votre promotion',
                'required' => false,
            ]);
    }

    private function getPromotionChoices(): array
    {
        $promotions = $this->promotionRepository->findAll();
        $choices = [];

        foreach ($promotions as $promotion) {
            $label = $promotion->getNiveauPromotion() . ' - ' . $promotion->getEnseignement();
            if (!$label) {
                $label = 'Unknown Promotion';  
            }
            $choices[$label] = $promotion->getId();
        }

        return $choices;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

