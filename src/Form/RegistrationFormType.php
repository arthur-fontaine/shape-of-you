<?php

namespace App\Form;

use App\Entity\User;
use App\Enum\Gender;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('name', TextType::class, [
                'label' => 'name',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un nom',
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label'=> 'Accepter les conditions générales',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les conditions générales',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('gender', EnumType::class, [
                'class' => Gender::class,
                'label' => 'Gender',
            ])
            ->add('birthday', DateType::class, [
                'label' => 'birthday',
            ])
            ->add('country_iso2', ChoiceType::class, [
                'choices' => [
                    'France' => 'FR',
                ],
                'label' => 'country',
            ])
            ->add('weightKg', TextType::class, [
                'label' => 'weight (Kg)',
            ])
            ->add('sizeCm', TextType::class, [
                'label' => 'size (cm)',
            ])
            ->add('hipMeasurementCm', TextType::class, [
                'label' => 'hip (cm)',
            ])
            ->add('chestMeasurementCm', TextType::class, [
                'label' => 'chest (cm)',
            ])
            ->add('waistMeasurementCm', TextType::class, [
                'label' => 'waist (cm)',
            ])
            ->add('armMeasurementCm', TextType::class, [
                'label' => 'arm (cm)',
            ])
            ->add('legMeasurementCm', TextType::class, [
                'label' => 'leg (cm)',
            ])
            ->add('footMeasurementCm', TextType::class, [
                'label' => 'foot (cm)',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
